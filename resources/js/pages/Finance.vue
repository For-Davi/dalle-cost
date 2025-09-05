<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import Button from '@/components/ui/button/Button.vue'
  import { Ellipsis, Pencil, Trash } from 'lucide-vue-next'
  import { router } from '@inertiajs/vue3'
  import { Plus } from 'lucide-vue-next'
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
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
  } from '@/components/ui/dropdown-menu'
  import { ref } from 'vue'
  import { toast } from 'vue-sonner'
  import FormFinance from '@/components/form/FormFinance.vue'

  defineOptions({
    name: 'Finance',
  })

  const props = defineProps({
    finances: {
      type: Array,
      required: true,
    },
  })

  const dataEdit = ref(null)
  const isFormFinanceOpen = ref(false)

  const setDataEdit = (finance, open) => {
    dataEdit.value = finance
    isFormFinanceOpen.value = open
  }
  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir esta renda?')) {
      router.delete(route('finance.destroy', { financeID: id }))
      toast.success('Finança excluída')
    }
  }
  const formatCurrency = value => {
    if (value === null || value === undefined) return 'R$ 0,00'
    return Number(value).toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    })
  }
  const openCreateForm = () => {
    dataEdit.value = null
    isFormFinanceOpen.value = true
  }
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Finanças</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormFinance
          :finance="dataEdit"
          v-model:open="isFormFinanceOpen"
          @close="setDataEdit(null, false)"
        />
        <Button class="cursor-pointer" @click="openCreateForm">
          <span>Nova finança</span>
          <Plus class="w-4 h-4 mr-2" />
        </Button>
      </section>
      <section class="mt-2">
        <Table class="border-2 bg-white">
          <TableCaption>Lista de finanças</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="w-[40%] pl-8"> Período </TableHead>
              <TableHead class="w-[40%] pl-8"> Valor </TableHead>
              <TableHead class="w-[20%] pr-8 text-right">Ação</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in finances ?? []" :key="index">
              <TableCell class="pl-8">
                {{ item.period }}
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
        </Table>
      </section>
    </div>
  </PanelLayout>
</template>
