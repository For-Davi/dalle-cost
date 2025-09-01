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

  defineOptions({
    name: 'FormUser',
  })
</script>

<template>
  <Dialog>
    <DialogTrigger as-child>
      <Button class="cursor-pointer">
        <span>Novo usuário</span> <Plus class="w-4 h-4 mr-2" />
      </Button>
    </DialogTrigger>
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle class="font-bold">Usuário</DialogTitle>
        <DialogDescription>
          Formulário para criação ou atualização de usuário com acesso ao
          sistema
        </DialogDescription>
      </DialogHeader>
      <div class="grid gap-4 py-4">
        <div class="flex flex-col space-y-1.5">
          <Label for="email">E-mail</Label>
          <Input id="email" placeholder="E-mail" />
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label for="password">Senha</Label>
          <Input id="password" placeholder="Senha" type="password" />
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label for="confirm-password">Confirme a Senha</Label>
          <Input
            id="confirm-password"
            placeholder="Confirme a senha"
            type="password"
          />
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Nível de Acesso</Label>
          <Select>
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Selecione um tipo de acesso" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem value="administrador">Administrador</SelectItem>
                <SelectItem value="visualizador">Visualizador</SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
      </div>
      <DialogFooter>
        <Button type="submit" class="w-full"> Salvar </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
