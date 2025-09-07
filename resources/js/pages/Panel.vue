<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import { Bar } from 'vue-chartjs'
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
  } from 'chart.js'
  import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
  } from '@/components/ui/select'
  import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
  } from '@/components/ui/table'
  import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
  } from '@/components/ui/pagination'
  import { ref, computed } from 'vue'
  import { usePage } from '@inertiajs/vue3'
  import MovementDetails from '@/components/general/MovementDetails.vue'
  import { Eye } from 'lucide-vue-next'
  import { Search } from 'lucide-vue-next'
  import { Input } from '@/components/ui/input'
  import Button from '@/components/ui/button/Button.vue'
  import Label from '@/components/ui/label/Label.vue'
  import TableFooter from '@/components/ui/table/TableFooter.vue'

  const props = defineProps({
    movements: {
      type: Array,
      required: true,
    },
    members: {
      type: Array,
      required: true,
    },
    origins: {
      type: Array,
      required: true,
    },
    categories: {
      type: Array,
      required: true,
    },
    years: {
      type: Array,
      required: true,
    },
    receipts: {
      type: Array,
      required: true,
    },
  })

  ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
  )

  const search = ref('')
  const dataMovement = ref(null)
  const itensPerPage = ref(10)
  const currentPage = ref(1)
  const showMovementDetails = ref(false)
  const filters = ref({
    memberID: 0,
    originID: 0,
    categoryID: 0,
    year: null,
    month: null,
  })

  const formatCurrency = value => {
    if (value === null || value === undefined) return 'R$ 0,00'
    return Number(value).toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    })
  }
  const showMovement = (open, movement) => {
    dataMovement.value = movement
    showMovementDetails.value = open
  }
  const isNotCurrentYear = year => {
    return year !== currentYear.value
  }
  const setPage = page => {
    currentPage.value = page
  }

  const page = usePage()
  const user = computed(() => page.props.auth.user)
  const getMovements = computed(() => {
    let list = props.movements

    if (search.value !== '') {
      const query = search.value.toLowerCase()
      list = list.filter(
        item =>
          item.date_buy?.toLowerCase().includes(query) ||
          item.period?.toLowerCase().includes(query) ||
          item.value?.toString().includes(query) ||
          item.description?.toLowerCase().includes(query) ||
          item.category?.name?.toLowerCase().includes(query) ||
          item.member?.name?.toLowerCase().includes(query) ||
          item.origin?.name?.toLowerCase().includes(query)
      )
    }

    if (filters.value.memberID === null) {
      list = list.filter(item => item.member_id === null)
    } else if (filters.value.memberID > 0) {
      list = list.filter(item => item.member_id === filters.value.memberID)
    }

    if (filters.value.categoryID === null) {
      list = list.filter(item => item.category_id === null)
    } else if (filters.value.categoryID > 0) {
      list = list.filter(item => item.category_id === filters.value.categoryID)
    }

    if (filters.value.originID === null) {
      list = list.filter(item => item.origin_id === null)
    } else if (filters.value.originID > 0) {
      list = list.filter(item => item.origin_id === filters.value.originID)
    }

    if (filters.value.month !== null) {
      const selectedMonth = parseInt(filters.value.month)

      list = list.filter(item => {
        if (!item.period) return false

        const [monthStr, yearStr] = item.period.split('/')
        const periodMonth = parseInt(monthStr)

        return periodMonth === selectedMonth
      })
    }

    if (filters.value.year === null) {
      const now = new Date()
      const currentBrazilYear = new Date(
        now.toLocaleString('en-US', { timeZone: 'America/Sao_Paulo' })
      ).getFullYear()

      list = list.filter(item => {
        const periodYear = parseInt(item.period?.split('/')[1])
        return periodYear === currentBrazilYear
      })
    } else {
      const selectedYear = parseInt(filters.value.year)

      list = list.filter(item => {
        const periodYear = parseInt(item.period?.split('/')[1])
        return periodYear === selectedYear
      })
    }

    return list
  })
  const paginatedMovements = computed(() => {
    const start = (currentPage.value - 1) * itensPerPage.value
    const end = start + itensPerPage.value
    return getMovements.value.slice(start, end)
  })
  const currentYear = computed(() => {
    return String(
      new Date(
        new Date().toLocaleString('en-US', { timeZone: 'America/Sao_Paulo' })
      ).getFullYear()
    )
  })
  const currentPeriod = computed(() => {
    const now = new Date()
    const month = String(now.getMonth() + 1).padStart(2, '0')
    const year = now.getFullYear()
    return `${month}/${year}`
  })
  const currentMonthTotal = computed(() => {
    return getMovements.value.reduce(
      (acc, item) => acc + Number(item.value || 0),
      0
    )
  })
  const currentReceipt = computed(() => {
    let filteredReceipts = [...props.receipts]

    if (filters.value.memberID !== 0) {
      const memberId = Number(filters.value.memberID)
      filteredReceipts = filteredReceipts.filter(
        receipt => Number(receipt.member_id) === memberId
      )
    }

    if (filters.value.month !== null) {
      const selectedMonth = parseInt(filters.value.month)

      filteredReceipts = filteredReceipts.filter(receipt => {
        if (!receipt.period) return false

        const [monthStr, yearStr] = receipt.period.split('/')
        const periodMonth = parseInt(monthStr)

        return periodMonth === selectedMonth
      })
    }

    if (filters.value.year !== null) {
      const selectedYear = parseInt(filters.value.year)

      filteredReceipts = filteredReceipts.filter(receipt => {
        if (!receipt.period) return false

        const [monthStr, yearStr] = receipt.period.split('/')
        const periodYear = parseInt(yearStr)

        return periodYear === selectedYear
      })
    }

    const totalValue = filteredReceipts.reduce((sum, receipt) => {
      return sum + parseFloat(receipt.value)
    }, 0)

    return totalValue.toFixed(2)
  })
  const remainingAmount = computed(() => {
    return currentReceipt.value - currentMonthTotal.value
  })
  const monthlyTotals = computed(() => {
    const totals = Array(12).fill(0)
    const filteredMovements = getMovements.value

    filteredMovements.forEach(movement => {
      const [month] = movement.period.split('/')
      const monthIndex = parseInt(month, 10) - 1

      totals[monthIndex] += parseFloat(movement.value)
    })

    return totals
  })

  const chartData = computed(() => {
    return {
      labels: [
        'Janeiro',
        'Fevereiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro',
      ],
      datasets: [
        {
          label: 'Dívidas',
          data: monthlyTotals.value,
          backgroundColor: '#3b82f6',
          borderRadius: 5,
          barPercentage: 0.7,
        },
      ],
    }
  })
  const chartOptions = ref({
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Relatório de dívidas',
      },
    },
  })

  const months = computed(() => [
    { value: 1, label: 'Janeiro' },
    { value: 2, label: 'Fevereiro' },
    { value: 3, label: 'Março' },
    { value: 4, label: 'Abril' },
    { value: 5, label: 'Maio' },
    { value: 6, label: 'Junho' },
    { value: 7, label: 'Julho' },
    { value: 8, label: 'Agosto' },
    { value: 9, label: 'Setembro' },
    { value: 10, label: 'Outubro' },
    { value: 11, label: 'Novemebro' },
    { value: 12, label: 'Dezembro' },
  ])
</script>
<template>
  <PanelLayout>
    <section class="px-6 mt-2">
      <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
      <div class="flex gap-x-2 bg-white p-4 rounded-lg shadow">
        <div class="flex flex-col space-y-1.5">
          <Label>Devedor</Label>
          <Select v-model="filters.memberID">
            <SelectTrigger>
              <SelectValue placeholder="Selecione um devedor" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Sem devedor</SelectItem>
                <SelectItem :value="0">Todos</SelectItem>
                <SelectItem
                  v-for="(item, index) in members ?? []"
                  :value="item.id"
                  :key="index"
                  >{{ item.name }}</SelectItem
                >
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Origem</Label>
          <Select v-model="filters.originID">
            <SelectTrigger>
              <SelectValue placeholder="Selecione uma origem" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Sem origem</SelectItem>
                <SelectItem :value="0">Todos</SelectItem>
                <SelectItem
                  v-for="(item, index) in origins ?? []"
                  :value="item.id"
                  :key="index"
                  >{{ item.name }}</SelectItem
                >
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Categoria</Label>
          <Select v-model="filters.categoryID">
            <SelectTrigger>
              <SelectValue placeholder="Selecione uma categoria" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Sem categoria</SelectItem>
                <SelectItem :value="0">Todos</SelectItem>
                <SelectItem
                  v-for="(item, index) in categories ?? []"
                  :value="item.id"
                  :key="index"
                  >{{ item.name }}</SelectItem
                >
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Mês</Label>
          <Select v-model="filters.month">
            <SelectTrigger>
              <SelectValue placeholder="Selecione um mês" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Todos os mêses</SelectItem>
                <SelectItem
                  v-for="(item, index) in months"
                  :value="item.value"
                  :key="index"
                  >{{ item.label }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Ano</Label>
          <Select v-model="filters.year">
            <SelectTrigger>
              <SelectValue placeholder="Selecione um ano" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Ano atual</SelectItem>
                <SelectItem
                  v-for="(item, index) in years ?? []"
                  v-show="isNotCurrentYear(item)"
                  :value="item"
                  :key="index"
                  >{{ item }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
      </div>
    </section>
    <section class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Divídas (Saída)</h3>
          <p class="text-3xl font-bold">
            {{ formatCurrency(currentMonthTotal) }}
          </p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Recebimentos (Entrada)</h3>
          <p class="text-3xl font-bold">{{ formatCurrency(currentReceipt) }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Saldo</h3>
          <p
            class="text-3xl font-bold"
            :class="{
              'text-green-500': remainingAmount >= 0,
              'text-red-500': remainingAmount < 0,
            }"
          >
            {{ formatCurrency(remainingAmount) }}
          </p>
        </div>
      </div>
    </section>
    <section class="px-6">
      <div class="bg-white p-4 rounded-lg shadow">
        <Bar
          id="my-chart-id"
          :options="chartOptions"
          :data="chartData"
          style="height: 400px; max-height: 400px"
        />
      </div>
    </section>
    <section class="my-2 px-6">
      <div class="bg-white p-4 rounded-lg shadow">
        <Table class="border-2 mt-2 bg-white">
          <TableCaption
            >Lista de movimentações : {{ getMovements.length }}</TableCaption
          >
          <TableHeader>
            <TableRow>
              <TableHead class="pl-8"> Data de compra </TableHead>
              <TableHead class="pl-8"> Período </TableHead>
              <TableHead class="pl-8"> Devedor </TableHead>
              <TableHead class="pl-8"> Origem </TableHead>
              <TableHead class="pl-8"> Categoria </TableHead>
              <TableHead class="pl-8"> Valor </TableHead>
              <TableHead class="pr-8 text-right">Ação</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in paginatedMovements" :key="index">
              <TableCell class="pl-8">
                {{ item.date_buy }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.period }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.member ? item.member.name : 'Sem responsável' }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.origin ? item.origin.name : 'Sem origem' }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.category ? item.category.name : 'Sem categoria' }}
              </TableCell>
              <TableCell class="pl-8">
                {{ formatCurrency(item.value) }}
              </TableCell>
              <TableCell class="text-center">
                <Button
                  variant="ghost"
                  class="cursor-pointer"
                  @click="showMovement(true, item)"
                >
                  <Eye class="h-4 mr-2" />
                </Button>
              </TableCell>
            </TableRow>
          </TableBody>
          <TableFooter class="bg-white">
            <Pagination
              class="my-2"
              :items-per-page="itensPerPage"
              :total="getMovements.length"
              :default-page="1"
              @update:page="setPage"
            >
              <PaginationContent v-slot="{ items }">
                <PaginationPrevious />
                <template v-for="(item, index) in items" :key="index">
                  <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                  >
                    {{ item.value }}
                  </PaginationItem>
                </template>
                <PaginationEllipsis :index="4" />
                <PaginationNext />
              </PaginationContent>
            </Pagination>
          </TableFooter>
        </Table>
      </div>
    </section>
    <MovementDetails
      :movement="dataMovement"
      v-model:open="showMovementDetails"
      @close="showMovement(false, null)"
    />
  </PanelLayout>
</template>
