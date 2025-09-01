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
    name: 'FormMember',
  })

  const props = defineProps({
    member: {
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

  const isEditing = computed(() => !!props.member)

  const submit = () => {
    if (isEditing.value) {
      form.put(route('member.update', { memberID: props.member.id }), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Membro atualizado')
        },
      })
    } else {
      form.post(route('member.store'), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Membro criado')
        },
      })
    }
  }

  watch(
    () => props.open,
    newVal => {
      form.reset()
      if (props.member) {
        form.name = props.member.name
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
            {{ isEditing ? 'Editar Membro' : 'Novo Membro' }}
          </DialogTitle>
          <DialogDescription>
            {{
              isEditing ? 'Atualize os dados do membro' : 'Crie um novo membro'
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
