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
  import { Textarea } from '@/components/ui/textarea'
  import { Label } from '@/components/ui/label'
  import { useForm } from '@inertiajs/vue3'
  import { ref, watch, computed } from 'vue'
  import { toast } from 'vue-sonner'

  defineOptions({
    name: 'FormMovement',
  })

  const props = defineProps({
    movement: {
      type: Object,
      required: true,
      default: null,
    },
    origins: {
      type: Array,
      required: true,
      default: [],
    },
    members: {
      type: Array,
      required: true,
      default: [],
    },
    categories: {
      type: Array,
      required: true,
      default: [],
    },
    open: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])

  const form = useForm({
    value: '',
    period: '',
    dateBuy: '',
    description: '',
    memberID: null,
    originID: null,
    categoryID: null,
  })

  const isEditing = computed(() => !!props.movement)

  const formatPayday = event => {
    let value = event.target.value.replace(/\D/g, '')
  }
  const submit = () => {
    if (isEditing.value) {
      form.put(route('data.update', { dataID: props.movement.id }), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Movimentação atualizada')
        },
      })
    } else {
      form.post(route('data.store'), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Movimentação criada')
        },
      })
    }
  }

  watch(
    () => props.open,
    newVal => {
      form.reset()
      if (props.movement) {
        form.value = props.origin.value
        form.period = props.origin.period
        form.description = props.origin.description ?? ''
        form.memberID = props.movement.member_id
        form.originID = props.movement.origin_id
        form.categoryID = props.movement.category_id
        form.dateBuy = props.movement.date_buy
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
            {{ isEditing ? 'Editar Movimentação' : 'Nova Movimentação' }}
          </DialogTitle>
          <DialogDescription>
            {{
              isEditing
                ? 'Atualize os dados da movimentação'
                : 'Crie uma nova movimentação'
            }}
          </DialogDescription>
        </DialogHeader>
        <div class="grid gap-4 py-4">
          <div class="flex flex-col space-y-1.5">
            <Label for="value">Valor</Label>
            <Input
              id="value"
              placeholder="R$ Valor da movimentação"
              v-model="form.value"
            />
            <span v-if="form.errors.value" class="text-red-500 text-sm">
              {{ form.errors.value }}
            </span>
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label for="dateBuy">Data</Label>
            <Input
              id="dateBuy"
              placeholder="Data da compra"
              v-model="form.dateBuy"
            />
            <span v-if="form.errors.dateBuy" class="text-red-500 text-sm">
              {{ form.errors.dateBuy }}
            </span>
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label>Devedor</Label>
            <Select v-model="form.memberID">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="Selecione um devedor" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectItem :value="null">Sem devedor</SelectItem>
                  <SelectItem
                    v-for="(item, index) in members"
                    :value="item.id"
                    :key="index"
                    >{{ item.name }}</SelectItem
                  >
                </SelectGroup>
              </SelectContent>
            </Select>
            <span v-if="form.errors.memberID" class="text-red-500 text-sm">
              {{ form.errors.memberID }}
            </span>
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label>Origem</Label>
            <Select v-model="form.originID">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="Selecione uma origem" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectItem :value="null">Sem origem</SelectItem>
                  <SelectItem
                    v-for="(item, index) in origins"
                    :value="item.id"
                    :key="index"
                    >{{ item.name }}</SelectItem
                  >
                </SelectGroup>
              </SelectContent>
            </Select>
            <span v-if="form.errors.originID" class="text-red-500 text-sm">
              {{ form.errors.originID }}
            </span>
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label>Categoria</Label>
            <Select v-model="form.categoryID">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="Selecione uma categoria" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectItem :value="null">Sem categoria</SelectItem>
                  <SelectItem
                    v-for="(item, index) in categories"
                    :value="item.id"
                    :key="index"
                    >{{ item.name }}</SelectItem
                  >
                </SelectGroup>
              </SelectContent>
            </Select>
            <span v-if="form.errors.categoryID" class="text-red-500 text-sm">
              {{ form.errors.categoryID }}
            </span>
          </div>
          <div class="flex flex-col space-y-1.5">
            <Label for="description">Descrição</Label>
            <Textarea
              placeholder="Anote observações dessa movimentação"
              class="resize-none"
              v-model="form.description"
            />
            <span v-if="form.errors.description" class="text-red-500 text-sm">
              {{ form.errors.description }}
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
