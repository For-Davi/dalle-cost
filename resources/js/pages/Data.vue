<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import Button from '@/components/ui/button/Button.vue'
  import { Ellipsis, Pencil, Trash } from 'lucide-vue-next'
  import { router } from '@inertiajs/vue3'
  import { Plus, Eye } from 'lucide-vue-next'
  import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
    TableFooter,
  } from '@/components/ui/table'
  import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
  } from '@/components/ui/dropdown-menu'
  import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
  } from '@/components/ui/pagination'
  import { computed, ref } from 'vue'
  import { toast } from 'vue-sonner'
  import FormMovement from '@/components/form/FormMovement.vue'
  import MovementDetails from '@/components/general/MovementDetails.vue'
  import { Search } from 'lucide-vue-next'
  import { Input } from '@/components/ui/input'

  defineOptions({
    name: 'Data',
  })

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
  })

  const itensPerPage = ref(10)
  const search = ref('')
  const dataEdit = ref(null)
  const isFormMovementOpen = ref(false)
  const dataMovement = ref(null)
  const showMovementDetails = ref(false)
  const currentPage = ref(1)

  const setDataEdit = (movement, open) => {
    dataEdit.value = movement
    isFormMovementOpen.value = open
  }
  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir esta movimentação?')) {
      router.delete(route('data.destroy', { dataID: id }))
      toast.success('Movimentação excluída')
    }
  }
  const showMovement = (open, movement) => {
    dataMovement.value = movement
    showMovementDetails.value = open
  }
  const openCreateForm = () => {
    dataEdit.value = null
    isFormMovementOpen.value = true
  }
  const formatCurrency = value => {
    if (value === null || value === undefined) return 'R$ 0,00'
    return Number(value).toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    })
  }
  const setPage = page => {
    currentPage.value = page
  }
  const getInstallmentColor = installment => {
    if (!installment) return ''

    const parts = installment.split('/')
    if (parts.length !== 2) return 'text-red-500'

    const left = parseInt(parts[0])
    const right = parseInt(parts[1])

    if (isNaN(left) || isNaN(right)) return 'text-red-500'

    return left === right ? 'text-green-500' : 'text-red-500'
  }

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
          item.origin?.name?.toLowerCase().includes(query) ||
          (item.installment && item.installment.toLowerCase().includes(query))
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
</script>

<template>
  <PanelLayout>
    <div class="p-4 sm:p-6">
      <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Dados</h1>

      <section
        class="bg-white p-3 sm:p-4 rounded-lg shadow flex flex-col sm:flex-row gap-3 sm:gap-2 items-stretch sm:items-center"
      >
        <FormMovement
          :open="isFormMovementOpen"
          :movement="dataEdit"
          :members="members"
          :origins="origins"
          :categories="categories"
          @close="setDataEdit(null, false)"
        />

        <div
          class="relative w-full sm:max-w-sm items-center order-2 sm:order-1"
        >
          <Input
            id="search"
            type="text"
            v-model="search"
            placeholder="Filtre os resultados"
            class="pl-10 text-sm sm:text-base"
          />
          <span
            class="absolute start-0 inset-y-0 flex items-center justify-center px-2"
          >
            <Search class="size-4 text-muted-foreground" />
          </span>
        </div>

        <Button
          class="cursor-pointer text-sm sm:text-base order-1 sm:order-2 w-full sm:w-auto"
          @click="openCreateForm"
        >
          <Plus class="w-4 h-4 mr-1 sm:mr-2" />
          <span>Novo lançamento</span>
        </Button>
      </section>

      <section class="mt-2 overflow-x-auto">
        <Table class="border-2 bg-white min-w-full">
          <TableCaption class="text-sm sm:text-base px-2 sm:px-4">
            Lista de movimentações : {{ getMovements.length }}
          </TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="text-sm sm:text-base px-2 sm:pl-4"
                >Data</TableHead
              >
              <TableHead
                class="text-sm sm:text-base px-2 sm:pl-4 hidden md:table-cell"
                >Parcela</TableHead
              >
              <TableHead
                class="text-sm sm:text-base px-2 sm:pl-4 hidden lg:table-cell"
                >Período</TableHead
              >
              <TableHead class="text-sm sm:text-base px-2 sm:pl-4"
                >Devedor</TableHead
              >
              <TableHead
                class="text-sm sm:text-base px-2 sm:pl-4 hidden xl:table-cell"
                >Origem</TableHead
              >
              <TableHead
                class="text-sm sm:text-base px-2 sm:pl-4 hidden xl:table-cell"
                >Categoria</TableHead
              >
              <TableHead class="text-sm sm:text-base px-2 sm:pl-4"
                >Valor</TableHead
              >
              <TableHead class="text-sm sm:text-base px-2 sm:pr-4 text-right"
                >Ação</TableHead
              >
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in paginatedMovements" :key="index">
              <TableCell class="text-sm sm:text-base px-2 sm:pl-4">
                {{ item.date_buy }}
              </TableCell>
              <TableCell
                class="text-sm sm:text-base px-2 sm:pl-4 font-bold hidden md:table-cell"
                :class="getInstallmentColor(item.installment)"
              >
                {{ item.installment ?? '' }}
              </TableCell>
              <TableCell
                class="text-sm sm:text-base px-2 sm:pl-4 hidden lg:table-cell"
              >
                {{ item.period }}
              </TableCell>
              <TableCell class="text-sm sm:text-base px-2 sm:pl-4">
                {{ item.member ? item.member.name : 'Sem responsável' }}
              </TableCell>
              <TableCell
                class="text-sm sm:text-base px-2 sm:pl-4 hidden xl:table-cell"
              >
                {{ item.origin ? item.origin.name : 'Sem origem' }}
              </TableCell>
              <TableCell
                class="text-sm sm:text-base px-2 sm:pl-4 hidden xl:table-cell"
              >
                {{ item.category ? item.category.name : 'Sem categoria' }}
              </TableCell>
              <TableCell class="text-sm sm:text-base px-2 sm:pl-4">
                {{ formatCurrency(item.value) }}
              </TableCell>
              <TableCell class="px-2 sm:pr-4 sm:pr-8 text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" class="cursor-pointer h-8 w-8 p-0">
                      <Ellipsis class="w-4 h-4" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent class="w-48 sm:w-56">
                    <DropdownMenuLabel class="text-xs sm:text-sm"
                      >Opções</DropdownMenuLabel
                    >
                    <DropdownMenuSeparator />
                    <DropdownMenuGroup>
                      <DropdownMenuItem
                        @click="showMovement(true, item)"
                        class="text-xs sm:text-sm"
                      >
                        <Eye class="w-3 h-3 sm:w-4 sm:h-4 mr-2" />
                        <span>Detalhes</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem
                        @click="setDataEdit(item, true)"
                        class="text-xs sm:text-sm"
                      >
                        <Pencil class="w-3 h-3 sm:w-4 sm:h-4 mr-2" />
                        <span>Editar</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem
                        class="text-red-500 text-xs sm:text-sm"
                        @click="exclude(item.id)"
                      >
                        <Trash class="w-3 h-3 sm:w-4 sm:h-4 mr-2" />
                        <span>Excluir</span>
                      </DropdownMenuItem>
                    </DropdownMenuGroup>
                  </DropdownMenuContent>
                </DropdownMenu>
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
              v-slot="{ page }"
            >
              <PaginationContent v-slot="{ items }">
                <PaginationPrevious />
                <template v-for="(item, index) in items" :key="index">
                  <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                    :class="{
                      'border-1 border-primary': item.value === page,
                    }"
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
      </section>

      <MovementDetails
        :movement="dataMovement"
        v-model:open="showMovementDetails"
        @close="showMovement(false, null)"
      />
    </div>
  </PanelLayout>
</template>
