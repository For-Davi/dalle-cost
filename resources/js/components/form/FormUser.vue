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
  import { Plus } from 'lucide-vue-next'
  import { Label } from '@/components/ui/label'
  import { useForm } from '@inertiajs/vue3'
  import { ref } from 'vue'

  defineOptions({
    name: 'FormUser',
  })

  const form = useForm({
    name: '',
    email: '',
    password: '',
    role: '',
  })
  const open = ref(false)

  const submit = () => {
    form.post(route('user.store'), {
      onSuccess: () => {
        form.reset()
        open.value = false
      },
    })
  }
</script>

<template>
  <Dialog v-model:open="open">
    <DialogTrigger as-child>
      <Button class="cursor-pointer">
        <span>Novo usuário</span> <Plus class="w-4 h-4 mr-2" />
      </Button>
    </DialogTrigger>
    <DialogContent class="sm:max-w-[425px]">
      <form @submit.prevent="submit">
        <DialogHeader>
          <DialogTitle class="font-bold">Usuário</DialogTitle>
          <DialogDescription>
            Formulário para criação ou atualização de usuário com acesso ao
            sistema
          </DialogDescription>
        </DialogHeader>
        <div class="grid gap-4 py-4">
          <div class="flex flex-col space-y-1.5">
            <Label for="name">Nome</Label>
            <Input id="name" placeholder="Nome completo" v-model="form.name" />
          </div>

          <div class="flex flex-col space-y-1.5">
            <Label for="email">E-mail</Label>
            <Input id="email" placeholder="E-mail" v-model="form.email" />
            <span v-if="form.errors.email" class="text-red-500 text-sm">
              {{ form.errors.email }}
            </span>
          </div>

          <div class="flex flex-col space-y-1.5">
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
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label>Nível de Acesso</Label>
            <Select v-model="form.role">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="Selecione um tipo de acesso" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectItem value="admin">Administrador</SelectItem>
                  <SelectItem value="viewer">Visualizador</SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <span v-if="form.errors.role" class="text-red-500 text-sm">
              {{ form.errors.role }}
            </span>
          </div>
        </div>

        <DialogFooter>
          <Button type="submit" class="w-full" :disabled="form.processing">
            Salvar
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
