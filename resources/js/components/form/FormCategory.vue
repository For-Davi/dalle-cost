<script setup>
  import { Button } from '@/components/ui/button'
  import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
  } from '@/components/ui/dialog'
  import { Input } from '@/components/ui/input'
  import { Label } from '@/components/ui/label'
  import { useForm } from '@inertiajs/vue3'
  import { ref, watch, computed } from 'vue'
  import { toast } from 'vue-sonner'

  defineOptions({
    name: 'FormCategory',
  })

  const props = defineProps({
    category: {
      type: Object,
      required: false,
      default: null,
    },
    open: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])

  const form = useForm({
    name: '',
  })

  const isEditing = computed(() => !!props.category)

  const submit = () => {
    if (isEditing.value) {
      form.put(route('category.update', { categoryID: props.category.id }), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Categoria atualizada')
        },
      })
    } else {
      form.post(route('category.store'), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Categoria criada')
        },
      })
    }
  }

  watch(
    () => props.open,
    newVal => {
      form.reset()
      if (props.category) {
        form.name = props.category.name
      }
    }
  )
</script>

<template>
  <Dialog :open="open" @update:open="value => $emit('close', value)">
    <DialogContent class="sm:max-w-[425px]">
      <form @submit.prevent="submit">
        <DialogHeader>
          <DialogTitle class="font-bold">
            {{ isEditing ? 'Editar Categoria' : 'Nova Categoria' }}
          </DialogTitle>
          <DialogDescription>
            {{
              isEditing
                ? 'Atualize os dados da categoria'
                : 'Crie uma nova categoria'
            }}
          </DialogDescription>
        </DialogHeader>
        <div class="grid gap-4 py-4">
          <div class="flex flex-col space-y-1.5">
            <Label for="name">Nome</Label>
            <Input id="name" placeholder="Nome completo" v-model="form.name" />
            <span v-if="form.errors.name" class="text-red-500 text-sm">
              {{ form.errors.name }}
            </span>
          </div>
        </div>
        <DialogFooter>
          <Button type="submit" class="w-full" :disabled="form.processing">
            {{ isEditing ? 'Atualizar' : 'Salvar' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
