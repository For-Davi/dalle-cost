<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import Button from '@/components/ui/button/Button.vue'
  import { Ellipsis, Pencil, Trash, Plus } from 'lucide-vue-next'
  import { router } from '@inertiajs/vue3'
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
  import FormReceipt from '@/components/form/FormReceipt.vue'
  import { Search } from 'lucide-vue-next'
  import { Input } from '@/components/ui/input'

  defineOptions({
    name: 'Receipt',
  })

  const props = defineProps({
    receipts: {
      type: Array,
      required: true,
    },
    members: {
      type: Array,
      required: true,
    },
  })

  const itensPerPage = ref(10)
  const search = ref('')
  const dataEdit = ref(null)
  const isFormReceiptOpen = ref(false)
  const dataReceipt = ref(null)
  const currentPage = ref(1)

  const setDataEdit = (receipt, open) => {
    dataEdit.value = receipt
    isFormReceiptOpen.value = open
  }
  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir este recebimento?')) {
      router.delete(route('receipt.destroy', { receiptID: id }))
      toast.success('Recebimento excluído')
    }
  }
  const openCreateForm = () => {
    dataEdit.value = null
    isFormReceiptOpen.value = true
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

  const getReceipts = computed(() => {
    if (search.value !== '') {
      return props.receipts.filter(item => {
        const query = search.value.toLowerCase()

        return (
          item.period?.toLowerCase().includes(query) ||
          item.date_receipt?.toLowerCase().includes(query) ||
          item.value?.toString().includes(query) ||
          item.description?.toLowerCase().includes(query) ||
          item.member?.name?.toLowerCase().includes(query)
        )
      })
    }

    return props.receipts
  })
</script>

<template>
  <PanelLayout>
    <div class="p-4 sm:p-6">
      <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Recebimentos</h1>

      <section
        class="bg-white p-3 sm:p-4 rounded-lg shadow flex flex-col sm:flex-row gap-3 sm:gap-2 items-stretch sm:items-center md:justify-between"
      >
        <FormReceipt
          :open="isFormReceiptOpen"
          :receipt="dataEdit"
          :members="members"
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
          <span>Novo recebimento</span>
        </Button>
      </section>

      <section class="mt-2 overflow-x-auto">
        <Table class="border-2 bg-white min-w-full">
          <TableCaption class="text-sm sm:text-base px-2 sm:px-4">
            Lista de recebimentos : {{ getReceipts.length }}
          </TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="text-sm sm:text-base px-2 sm:pl-4"
                >Data</TableHead
              >
              <TableHead
                class="text-sm sm:text-base px-2 sm:pl-4 hidden sm:table-cell"
                >Período</TableHead
              >
              <TableHead class="text-sm sm:text-base px-2 sm:pl-4"
                >Devedor</TableHead
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
            <TableRow v-for="(item, index) in getReceipts" :key="index">
              <TableCell class="text-sm sm:text-base px-2 sm:pl-4">
                {{ item.date_receipt }}
              </TableCell>
              <TableCell
                class="text-sm sm:text-base px-2 sm:pl-4 hidden sm:table-cell"
              >
                {{ item.period }}
              </TableCell>
              <TableCell class="text-sm sm:text-base px-2 sm:pl-4">
                {{ item.member ? item.member.name : 'Sem responsável' }}
              </TableCell>
              <TableCell class="text-sm sm:text-base px-2 sm:pl-4">
                {{ formatCurrency(item.value) }}
              </TableCell>
              <TableCell class="px-2 sm:pr-4 text-right">
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
              class="my-2 px-2 sm:px-4"
              :items-per-page="itensPerPage"
              :total="getReceipts.length"
              :default-page="1"
              @update:page="setPage"
            >
              <PaginationContent v-slot="{ items }">
                <PaginationPrevious class="h-8 w-8 p-0 sm:h-10 sm:w-10" />
                <template v-for="(item, index) in items" :key="index">
                  <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                    class="h-8 w-8 sm:h-10 sm:w-10 text-xs sm:text-sm"
                  >
                    {{ item.value }}
                  </PaginationItem>
                </template>
                <PaginationEllipsis
                  :index="4"
                  class="h-8 w-8 sm:h-10 sm:w-10"
                />
                <PaginationNext class="h-8 w-8 p-0 sm:h-10 sm:w-10" />
              </PaginationContent>
            </Pagination>
          </TableFooter>
        </Table>
      </section>
    </div>
  </PanelLayout>
</template>
