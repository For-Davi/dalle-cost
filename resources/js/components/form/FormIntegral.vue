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
  import { Users } from 'lucide-vue-next'

  defineOptions({
    name: 'FormIntegral',
  })
</script>

<template>
  <Dialog>
    <DialogTrigger as-child>
      <Button class="cursor-pointer">
        Integrantes <Users class="w-4 h-4 mr-2" />
      </Button>
    </DialogTrigger>
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Integrante</DialogTitle>
        <DialogDescription>
          Formulário para criação ou atualização de integrante como pagador ou
          recebedor
        </DialogDescription>
      </DialogHeader>
      <div class="grid gap-4 py-4">
        <div class="flex flex-col space-y-1.5">
          <Input id="password" placeholder="Nome do integrante" />
        </div>
      </div>
      <DialogFooter>
        <Button type="submit" class="w-full"> Salvar </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
