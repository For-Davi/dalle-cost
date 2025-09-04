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
  import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
  } from '@/components/ui/select'
  import { Input } from '@/components/ui/input'
  import { Label } from '@/components/ui/label'
  import { router, useForm } from '@inertiajs/vue3'
  import { ref, watch, computed } from 'vue'
  import { toast } from 'vue-sonner'

  defineOptions({
    name: 'FormUser',
  })

  const props = defineProps({
    user: {
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
    email: '',
    password: '',
  })

  const isEditing = computed(() => !!props.user)

  const submit = () => {
    if (isEditing.value) {
      form.put(route('user.update', { userID: props.user.id }), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Usuário atualizado')
        },
      })
    } else {
      form.post(route('user.store'), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Usuário criado')
        },
      })
    }
  }

  watch(
    () => props.open,
    newVal => {
      form.reset()
      if (props.user) {
        form.name = props.user.name
        form.email = props.user.email
        form.password = ''
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
            {{ isEditing ? 'Editar Usuário' : 'Novo Usuário' }}
          </DialogTitle>
          <DialogDescription>
            {{
              isEditing
                ? 'Atualize os dados do usuário'
                : 'Crie um novo usuário com acesso ao sistema'
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

          <div class="flex flex-col space-y-1.5">
            <Label for="email">E-mail</Label>
            <Input id="email" placeholder="E-mail" v-model="form.email" />
            <span v-if="form.errors.email" class="text-red-500 text-sm">
              {{ form.errors.email }}
            </span>
          </div>

          <div v-if="!props.user" class="flex flex-col space-y-1.5">
            <Label for="password">Senha</Label>
            <Input
              id="password"
              placeholder="Senha"
              type="password"
              v-model="form.password"
            />
            <span v-if="form.errors.password" class="text-red-500 text-sm">
              {{ form.errors.password }}
            </span>
            <p v-if="isEditing" class="text-xs text-gray-500">
              Deixe em branco para manter a senha atual
            </p>
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
