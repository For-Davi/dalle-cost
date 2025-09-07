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

  defineOptions({
    name: 'Users',
  })

  const props = defineProps({
    users: {
      type: Array,
      required: true,
    },
  })

  const dataEdit = ref(null)
  const isFormUserOpen = ref(false)

  const setDataEdit = (user, open) => {
    dataEdit.value = user
    isFormUserOpen.value = open
  }

  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
      router.delete(route('user.destroy', { userID: id }))
      toast.success('Usuário excluído')
    }
  }
  const openCreateForm = () => {
    dataEdit.value = null
    isFormUserOpen.value = true
  }
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Usuários</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormUser
          :user="dataEdit"
          v-model:open="isFormUserOpen"
          @close="setDataEdit(null, false)"
        />
        <Button class="cursor-pointer" @click="openCreateForm">
          <span>Novo usuário</span>
          <Plus class="w-4 h-4 mr-2" />
        </Button>
      </section>
      <section class="mt-2">
        <Table class="border-2 bg-white">
          <TableCaption>Lista de usuários : {{ users.length }}</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="w-[100px]"> Nome </TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Ação</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in users" :key="index">
              <TableCell>
                {{ item.name }}
              </TableCell>
              <TableCell>{{ item.email }}</TableCell>
              <TableCell>
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
