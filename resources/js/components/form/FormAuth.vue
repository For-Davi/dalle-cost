<script setup>
  import { Button } from '@/components/ui/button'
  import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
  } from '@/components/ui/card'
  import { Input } from '@/components/ui/input'
  import { Label } from '@/components/ui/label'
  import { useForm } from '@inertiajs/vue3'
  import { Loader2 } from 'lucide-vue-next'

  defineOptions({
    name: 'FormAuth',
  })

  const form = useForm({
    email: '',
    password: '',
  })

  const submit = () => {
    form.post(route('authenticate'), {
      onFinish: () => form.reset('password'),
    })
  }
</script>

<template>
  <Card class="w-[600px] max-w-[98vw]">
    <CardHeader>
      <CardTitle>Dalle Cost</CardTitle>
      <CardDescription>Gerenciamento de contas</CardDescription>
    </CardHeader>
    <CardContent>
      <form @submit.prevent="submit">
        <div class="grid items-center w-full gap-4">
          <div class="flex flex-col space-y-1.5">
            <Label for="email">Email</Label>
            <Input
              id="email"
              placeholder="Seu e-mail"
              type="email"
              v-model="form.email"
            />
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label for="password">Senha</Label>
            <Input
              id="password"
              placeholder="Sua senha"
              type="password"
              v-model="form.password"
            />
          </div>
          <div v-if="form.errors.email">
            <p class="mt-2 text-sm text-red-600">
              {{ form.errors.email }}
            </p>
          </div>
        </div>
      </form>
    </CardContent>
    <CardFooter class="flex justify-between px-6 pb-6">
      <Button
        type="submit"
        class="w-full cursor-pointer"
        :disabled="form.processing"
        @click="submit"
      >
        <div v-if="form.processing">
          <Loader2 class="w-4 h-4 mr-2 animate-spin" />
        </div>
        <div v-else>Entrar</div>
      </Button>
    </CardFooter>
  </Card>
</template>
