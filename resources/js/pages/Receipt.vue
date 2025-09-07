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
      router.delete(route('data.destroy', { dataID: id }))
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
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Recebimentos</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormReceipt
          :open="isFormReceiptOpen"
          :receipt="dataEdit"
          :members="members"
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
          <span>Novo recebimento</span>
          <Plus class="w-4 h-4 mr-2" />
        </Button>
      </section>
      <section class="mt-2">
        <Table class="border-2 bg-white">
          <TableCaption>Lista de recebimentos</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="pl-8"> Data de recebimento </TableHead>
              <TableHead class="pl-8"> Período </TableHead>
              <TableHead class="pl-8"> Devedor </TableHead>
              <TableHead class="pl-8"> Valor </TableHead>
              <TableHead class="pr-8 text-right">Ação</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in getMovements" :key="index">
              <TableCell class="pl-8">
                {{ item.date_receipt }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.period }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.member ? item.member.name : 'Sem responsável' }}
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
              :total="getReceipts.length"
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
    </div>
  </PanelLayout>
</template>
