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
  import FormMovement from '@/components/form/FormMovement.vue'
  import MovementDetails from '@/components/general/MovementDetails.vue'

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

  const dataEdit = ref(null)
  const isFormMovementOpen = ref(false)
  const dataMovement = ref(null)
  const showMovementDetails = ref(false)

  const setDataEdit = (movement, open) => {
    dataEdit.value = movement
    isFormMovementOpen.value = open
  }
  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir esta movimentação?')) {
      router.delete(route('data.destroy', { movementID: id }))
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
        <Button class="cursor-pointer" @click="openCreateForm">
          <span>Novo lançamento</span>
          <Plus class="w-4 h-4 mr-2" />
        </Button>
      </section>
      <section class="mt-2">
        <Table class="border-2 bg-white">
          <TableCaption>Lista de movimentações</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="pl-8"> Período </TableHead>
              <TableHead class="pl-8"> Devedor </TableHead>
              <TableHead class="pl-8"> Origem </TableHead>
              <TableHead class="pl-8"> Categoria </TableHead>
              <TableHead class="pl-8"> Valor </TableHead>
              <TableHead class="pr-8 text-right">Ação</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in movements" :key="index">
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
                {{ item.value }}
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
        </Table>
      </section>
      <MovementDetails
        :data="dataMovement"
        v-model:open="showMovementDetails"
        @close="showMovement(false, null)"
      />
    </div>
  </PanelLayout>
</template>
