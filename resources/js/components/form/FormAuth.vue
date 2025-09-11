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
  <Card
    class="w-[600px] max-w-[95vw] sm:max-w-[450px] md:max-w-[500px] lg:max-w-[600px] mx-auto"
  >
    <CardHeader class="px-6 pt-6 pb-4 sm:px-8 sm:pt-8">
      <CardTitle class="text-2xl sm:text-3xl text-center">Dalle Cost</CardTitle>
      <CardDescription class="text-sm sm:text-base text-center">
        Gerenciamento de contas
      </CardDescription>
    </CardHeader>

    <CardContent class="px-6 pb-4 sm:px-8">
      <form @submit.prevent="submit">
        <div class="grid items-center w-full gap-4">
          <div class="flex flex-col space-y-1.5">
            <Label for="email" class="text-sm sm:text-base">Email</Label>
            <Input
              id="email"
              placeholder="Seu e-mail"
              type="email"
              v-model="form.email"
              class="w-full text-sm sm:text-base"
              :disabled="form.processing"
            />
          </div>

          <div class="flex flex-col space-y-1.5">
            <Label for="password" class="text-sm sm:text-base">Senha</Label>
            <Input
              id="password"
              placeholder="Sua senha"
              type="password"
              v-model="form.password"
              class="w-full text-sm sm:text-base"
              :disabled="form.processing"
            />
          </div>

          <div v-if="form.errors.email" class="mt-2">
            <p class="text-sm text-red-600 sm:text-base">
              {{ form.errors.email }}
            </p>
          </div>

          <div v-if="form.errors.password" class="mt-2">
            <p class="text-sm text-red-600 sm:text-base">
              {{ form.errors.password }}
            </p>
          </div>
        </div>
      </form>
    </CardContent>

    <CardFooter class="flex justify-between px-6 pb-6 sm:px-8">
      <Button
        type="submit"
        class="w-full cursor-pointer text-sm sm:text-base py-3"
        :disabled="form.processing"
        @click="submit"
      >
        <div v-if="form.processing" class="flex items-center justify-center">
          <Loader2 class="w-4 h-4 mr-2 animate-spin" />
          <span>Entrando...</span>
        </div>
        <div v-else>Entrar</div>
      </Button>
    </CardFooter>
  </Card>
</template>
