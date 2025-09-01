<script setup lang="ts">
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
  import { Plus } from 'lucide-vue-next'

  defineOptions({
    name: 'FormUser',
  })
</script>

<template>
  <Dialog>
    <DialogTrigger as-child>
      <Button class="cursor-pointer">
        Novo usuário <Plus class="w-4 h-4 mr-2" />
      </Button>
    </DialogTrigger>
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Usuário</DialogTitle>
        <DialogDescription>
          Formulário para criação ou atualização de usuário com acesso ao
          sistema
        </DialogDescription>
      </DialogHeader>
      <div class="grid gap-4 py-4">
        <div class="flex flex-col space-y-1.5">
          <Input placeholder="E-mail" />
        </div>
        <div class="flex flex-col space-y-1.5">
          <Input placeholder="Senha" />
        </div>
        <div class="flex flex-col space-y-1.5">
          <Input placeholder="Confirme a senha" />
        </div>
      </div>
      <DialogFooter>
        <Button type="submit" class="w-full"> Salvar </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
