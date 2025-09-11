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
    <DialogContent
      class="sm:max-w-[450px] max-w-[95vw] max-h-[90vh] overflow-hidden"
    >
      <form @submit.prevent="submit" class="p-1">
        <DialogHeader class="px-4 pt-4">
          <DialogTitle class="font-bold text-lg sm:text-xl">
            {{ isEditing ? 'Editar Usuário' : 'Novo Usuário' }}
          </DialogTitle>
          <DialogDescription class="text-sm sm:text-base">
            {{
              isEditing
                ? 'Atualize os dados do usuário'
                : 'Crie um novo usuário com acesso ao sistema'
            }}
          </DialogDescription>
        </DialogHeader>

        <div class="grid gap-4 py-4 px-4">
          <!-- Nome -->
          <div class="flex flex-col space-y-1.5">
            <Label for="name" class="text-sm sm:text-base">Nome</Label>
            <Input
              id="name"
              placeholder="Nome completo"
              v-model="form.name"
              class="w-full text-sm sm:text-base"
              :disabled="form.processing"
            />
            <span
              v-if="form.errors.name"
              class="text-red-500 text-xs sm:text-sm"
            >
              {{ form.errors.name }}
            </span>
          </div>

          <!-- E-mail -->
          <div class="flex flex-col space-y-1.5">
            <Label for="email" class="text-sm sm:text-base">E-mail</Label>
            <Input
              id="email"
              placeholder="E-mail"
              v-model="form.email"
              class="w-full text-sm sm:text-base"
              :disabled="form.processing"
            />
            <span
              v-if="form.errors.email"
              class="text-red-500 text-xs sm:text-sm"
            >
              {{ form.errors.email }}
            </span>
          </div>

          <!-- Senha (apenas para novo usuário) -->
          <div v-if="!props.user" class="flex flex-col space-y-1.5">
            <Label for="password" class="text-sm sm:text-base">Senha</Label>
            <Input
              id="password"
              placeholder="Senha"
              type="password"
              v-model="form.password"
              class="w-full text-sm sm:text-base"
              :disabled="form.processing"
            />
            <span
              v-if="form.errors.password"
              class="text-red-500 text-xs sm:text-sm"
            >
              {{ form.errors.password }}
            </span>
          </div>

          <!-- Mensagem para edição -->
          <div
            v-if="isEditing && !props.user"
            class="flex flex-col space-y-1.5"
          >
            <p class="text-xs text-gray-500 sm:text-sm">
              Deixe em branco para manter a senha atual
            </p>
          </div>
        </div>

        <DialogFooter class="px-4 pb-4">
          <Button
            type="submit"
            class="w-full text-sm sm:text-base py-2"
            :disabled="form.processing"
          >
            {{ isEditing ? 'Atualizar' : 'Salvar' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
