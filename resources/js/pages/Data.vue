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
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Dados</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormMovement
          :open="isFormMovementOpen"
          :movement="dataEdit"
          :members="members"
          :origins="origins"
          :categories="categories"
          @close="setDataEdit(null, false)"
        />
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
        <Button class="cursor-pointer" @click="openCreateForm">
          <span>Novo lançamento</span>
          <Plus class="w-4 h-4 mr-2" />
        </Button>
      </section>
      <section class="mt-2">
        <Table class="border-2 bg-white">
          <TableCaption
            >Lista de movimentações : {{ getMovements.length }}</TableCaption
          >
          <TableHeader>
            <TableRow>
              <TableHead class="pl-8"> Data de compra </TableHead>
              <TableHead class="pl-8"> Parcela </TableHead>
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
              <TableCell class="pl-8 font-bold" :class="item.installment && item.installment === '1/1' ? 'text-green-500' : 'text-red-500'">
                {{ item.installment ?? '' }}
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
              <TableCell class="pr-8 text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" class="cursor-pointer">
                      <Ellipsis />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent class="w-56">
                    <DropdownMenuLabel>Opções</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuGroup>
                      <DropdownMenuItem @click="showMovement(true, item)">
                        <Eye class="w-4 h-4 mr-2" />
                        <span>Detalhes</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="setDataEdit(item, true)">
                        <Pencil class="w-4 h-4 mr-2" />
                        <span>Editar</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem
                        class="text-red-500"
                        @click="exclude(item.id)"
                      >
                        <Trash class="w-4 h-4 mr-2" color="red" />
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
      </section>
      <MovementDetails
        :movement="dataMovement"
        v-model:open="showMovementDetails"
        @close="showMovement(false, null)"
      />
    </div>
  </PanelLayout>
</template>
