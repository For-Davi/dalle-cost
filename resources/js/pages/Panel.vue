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
  })
  const chartData = ref({
    labels: [
      'Janeiro',
      'Fevereiro',
      'Março',
      'Abril',
      'Maio',
      'Junho',
      'Julho',
      'Setembro',
      'Outubro',
      'Novembro',
      'Dezembro',
    ],
    datasets: [
      {
        label: 'Dívidas',
        data: [40, 55, 32, 80, 60],
        backgroundColor: '#3b82f6',
      },
    ],
  })
  const chartOptions = ref({
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Relatório de divídas',
      },
    },
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
    if (search.value !== '') {
      return props.movements.filter(item => {
        const query = search.value.toLowerCase()

        return (
          item.date_buy?.toLowerCase().includes(query) ||
          item.period?.toLowerCase().includes(query) ||
          item.value?.toString().includes(query) ||
          item.description?.toLowerCase().includes(query) ||
          item.category?.name?.toLowerCase().includes(query) ||
          item.member?.name?.toLowerCase().includes(query) ||
          item.origin?.name?.toLowerCase().includes(query)
        )
      })
    }

    return props.movements
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
          <h3 class="text-lg font-semibold">Mês atual</h3>
          <p class="text-3xl font-bold">R$ 1.248,00</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Total pago (mês atual)</h3>
          <p class="text-3xl font-bold">R$ 1.100,00</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Resultado</h3>
          <p class="text-3xl font-bold text-red-300">R$ - 148,00</p>
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
        <div class="relative w-full max-w-sm items-center">
          <Input
            id="search"
            type="text"
            v-model="search"
            placeholder="Filtre os resultados"
            class="pl-10"
          />
          <span
            class="absolute start-0 inset-y-0 flex items-center justify-center px-2"
          >
            <Search class="size-4 text-muted-foreground" />
          </span>
        </div>
        <Table class="border-2 mt-2 bg-white">
          <TableCaption>Lista de movimentações</TableCaption>
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
                  class="cursor-pointer"
                  @click="showMovement(true, item)"
                >
                  <Eye class="h-4 mr-2" />
                </Button>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
        <Pagination
          class="my-2"
          v-slot="{ page }"
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
      </div>
    </section>
    <MovementDetails
      :movement="dataMovement"
      v-model:open="showMovementDetails"
      @close="showMovement(false, null)"
    />
  </PanelLayout>
</template>
