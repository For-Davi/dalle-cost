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
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Origens</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormOrigin
          :origin="dataEdit"
          :members="members"
          v-model:open="isFormOriginOpen"
          @close="setDataEdit(null, false)"
        />
        <Button class="cursor-pointer" @click="openCreateForm">
          <span>Nova origem</span>
          <Plus class="w-4 h-4 mr-2" />
        </Button>
      </section>
      <section class="mt-2">
        <Table class="border-2 bg-white">
          <TableCaption>Lista de origens : {{ origins.length }}</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="w-[30%] pl-8"> Nome </TableHead>
              <TableHead class="w-[30%] pl-8"> Responsável </TableHead>
              <TableHead class="w-[20%] pl-8"> Dia de pagamento </TableHead>
              <TableHead class="w-[20%] pr-8 text-right">Ação</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in origins" :key="index">
              <TableCell class="pl-8">
                {{ item.name }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.member ? item.member.name : 'Sem responsável' }}
              </TableCell>
              <TableCell class="pl-8">
                {{ item.payday }}
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
