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
  import FormOrigin from '@/components/form/FormOrigin.vue'

  defineOptions({
    name: 'Origins',
  })

  const props = defineProps({
    origins: {
      type: Array,
      required: true,
    },
    members: {
      type: Array,
      required: true,
    },
  })

  const dataEdit = ref(null)
  const isFormOriginOpen = ref(false)

  const setDataEdit = (origin, open) => {
    dataEdit.value = origin
    isFormOriginOpen.value = open
  }

  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir esta origem?')) {
      router.delete(route('origin.destroy', { originID: id }))
      toast.success('Origem excluída')
    }
  }

  const openCreateForm = () => {
    dataEdit.value = null
    isFormOriginOpen.value = true
  }
</script>

<template>
  <PanelLayout>
    <div class="p-4 sm:p-6">
      <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Origens</h1>

      <section
        class="bg-white p-3 sm:p-4 rounded-lg shadow flex justify-end gap-2"
      >
        <FormOrigin
          :origin="dataEdit"
          :members="members"
          v-model:open="isFormOriginOpen"
          @close="setDataEdit(null, false)"
        />
        <Button
          class="cursor-pointer text-sm sm:text-base w-full sm:w-auto"
          @click="openCreateForm"
        >
          <Plus class="w-4 h-4 mr-1 sm:mr-2" />
          <span>Nova origem</span>
        </Button>
      </section>

      <section class="mt-2 overflow-x-auto">
        <Table class="border-2 bg-white min-w-full">
          <TableCaption class="text-sm sm:text-base">
            Lista de origens : {{ origins.length }}
          </TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="text-sm sm:text-base px-2 sm:pl-4"
                >Nome</TableHead
              >
              <TableHead
                class="text-sm sm:text-base px-2 sm:pl-4 hidden sm:table-cell"
                >Responsável</TableHead
              >
              <TableHead
                class="text-sm sm:text-base px-2 sm:pl-4 hidden md:table-cell"
                >Dia de pagamento</TableHead
              >
              <TableHead class="text-sm sm:text-base px-2 sm:pr-4 text-right"
                >Ação</TableHead
              >
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in origins" :key="index">
              <TableCell class="text-sm sm:text-base px-2 sm:pl-4">
                {{ item.name }}
              </TableCell>
              <TableCell
                class="text-sm sm:text-base px-2 sm:pl-4 hidden sm:table-cell"
              >
                {{ item.member ? item.member.name : 'Sem responsável' }}
              </TableCell>
              <TableCell
                class="text-sm sm:text-base px-2 sm:pl-4 hidden md:table-cell"
              >
                {{ item.payday }}
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
        </Table>
      </section>
    </div>
  </PanelLayout>
</template>
