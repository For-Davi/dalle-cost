<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import Button from '@/components/ui/button/Button.vue'
  import FormUser from '@/components/form/FormUser.vue'
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
  import FormMember from '@/components/form/FormMember.vue'

  defineOptions({
    name: 'Members',
  })

  const props = defineProps({
    members: {
      type: Array,
      required: true,
    },
  })

  const dataEdit = ref(null)
  const isFormMemberOpen = ref(false)

  const setDataEdit = (member, open) => {
    dataEdit.value = member
    isFormMemberOpen.value = open
  }

  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir este membro?')) {
      router.delete(route('member.destroy', { memberID: id }))
      toast.success('Membro excluído')
    }
  }

  const openCreateForm = () => {
    dataEdit.value = null
    isFormMemberOpen.value = true
  }
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Membros</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormMember
          :member="dataEdit"
          v-model:open="isFormMemberOpen"
          @close="setDataEdit(null, false)"
        />
        <Button class="cursor-pointer" @click="openCreateForm">
          <span>Novo membro</span>
          <Plus class="w-4 h-4 mr-2" />
        </Button>
      </section>
      <section class="mt-2">
        <Table class="border-2 bg-white">
          <TableCaption>Lista de membros</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="w-[80%] pl-8"> Nome </TableHead>
              <TableHead class="w-[20%] pr-8 text-right">Ação</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in members" :key="index">
              <TableCell class="pl-8">
                {{ item.name }}
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
