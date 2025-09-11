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
  import FormCategory from '@/components/form/FormCategory.vue'

  defineOptions({
    name: 'Categories',
  })

  const props = defineProps({
    categories: {
      type: Array,
      required: true,
    },
  })

  const dataEdit = ref(null)
  const isFormCategoryOpen = ref(false)

  const setDataEdit = (category, open) => {
    dataEdit.value = category
    isFormCategoryOpen.value = open
  }

  const exclude = id => {
    if (confirm('Tem certeza que deseja excluir esta categoria?')) {
      router.delete(route('category.destroy', { categoryID: id }))
      toast.success('Categoria excluída')
    }
  }

  const openCreateForm = () => {
    dataEdit.value = null
    isFormCategoryOpen.value = true
  }
</script>

<template>
  <PanelLayout>
    <div class="p-4 sm:p-6">
      <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Categorias</h1>

      <section
        class="bg-white p-3 sm:p-4 rounded-lg shadow flex justify-end gap-2"
      >
        <FormCategory
          :category="dataEdit"
          v-model:open="isFormCategoryOpen"
          @close="setDataEdit(null, false)"
        />
        <Button
          class="cursor-pointer text-sm sm:text-base w-full sm:w-auto"
          @click="openCreateForm"
        >
          <span>Nova categoria</span>
          <Plus class="w-4 h-4 mr-1 sm:mr-2" />
        </Button>
      </section>

      <section class="mt-2 overflow-x-auto">
        <Table class="border-2 bg-white min-w-full">
          <TableCaption class="text-sm sm:text-base">
            Lista de categorias : {{ categories.length }}
          </TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="pl-4 sm:pl-8 w-full sm:w-[80%]">Nome</TableHead>
              <TableHead class="pr-4 sm:pr-8 text-right w-auto sm:w-[20%]"
                >Ação</TableHead
              >
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(item, index) in categories" :key="index">
              <TableCell class="pl-4 sm:pl-8 text-sm sm:text-base">
                {{ item.name }}
              </TableCell>
              <TableCell class="pr-4 sm:pr-8 text-right">
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
