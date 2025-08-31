<script setup>
import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"

</script>

<template>
  <Card class="w-[600px] max-w-[98vw]">
    <CardHeader>
      <CardTitle>Dalle Cost</CardTitle>
      <CardDescription>Gerenciamento de contas</CardDescription>
    </CardHeader>
    <CardContent>
      <form>
        <div class="grid items-center w-full gap-4">
          <div class="flex flex-col space-y-1.5">
            <Label for="email">Email</Label>
            <Input id="email" placeholder="Seu e-mail" />
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label for="password">Senha</Label>
            <Input id="password" placeholder="Sua senha" />
          </div>
        </div>
      </form>
    </CardContent>
    <CardFooter class="flex justify-between px-6 pb-6">
      <Button class="w-full cursor-pointer">Entrar</Button>
    </CardFooter>
  </Card>
</template>