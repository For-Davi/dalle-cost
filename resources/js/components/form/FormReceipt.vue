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
  import { ScrollArea } from '@/components/ui/scroll-area'
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
    receipt: {
      type: Object,
      required: false,
      default: null,
    },
    members: {
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
    dateReceipt: '',
    memberID: null,
  })

  const isEditing = computed(() => !!props.receipt)

  const formatPayday = event => {
    let value = event.target.value.replace(/\D/g, '')
  }
  const submit = () => {
    if (isEditing.value) {
      form.put(route('receipt.update', { receiptID: props.receipt.id }), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Recebimento atualizado')
        },
      })
    } else {
      form.post(route('receipt.store'), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Recebimento criado')
        },
      })
    }
  }
  const onlyNumbers = e => {
    const value = e.target.value

    if (/[0-9]/.test(e.key)) {
      const decimalIndex = value.indexOf('.')
      if (decimalIndex !== -1) {
        const decimals = value.substring(decimalIndex + 1)

        if (decimals.length >= 2 && e.target.selectionStart > decimalIndex) {
          e.preventDefault()
        }
      }
      return
    }

    if (e.key === '.' && !value.includes('.')) {
      return
    }

    e.preventDefault()
  }
  const formatDateReceipt = event => {
    let value = event.target.value

    value = value.replace(/\D/g, '')

    if (value.length > 2) {
      value = value.slice(0, 2) + '/' + value.slice(2)
    }
    if (value.length > 5) {
      value = value.slice(0, 5) + '/' + value.slice(5)
    }

    if (value.length === 10) {
      let [day, month, year] = value.split('/').map(v => parseInt(v, 10))

      if (year < 1900) year = 1900
      if (year > 2100) year = 2100

      if (month < 1) month = 1
      if (month > 12) month = 12

      const daysInMonth = new Date(year, month, 0).getDate()

      if (day < 1) day = 1
      if (day > daysInMonth) day = daysInMonth

      value =
        String(day).padStart(2, '0') +
        '/' +
        String(month).padStart(2, '0') +
        '/' +
        String(year).padStart(4, '0')
    }

    form.dateBuy = value
  }
  const formatDatePeriod = event => {
    let value = event.target.value

    value = value.replace(/\D/g, '')

    if (value.length > 2) {
      value = value.slice(0, 2) + '/' + value.slice(2)
    }

    if (value.length >= 2) {
      let month = parseInt(value.slice(0, 2))

      if (month > 12) {
        value = '12' + value.slice(2)
      }

      if (month === 0) {
        value = '01' + value.slice(2)
      }
    }

    form.period = value
  }

  watch(
    () => props.open,
    newVal => {
      form.reset()
      if (props.movement) {
        form.value = props.movement.value
        form.period = props.movement.period
        form.memberID = props.movement.member_id
        form.dateReceipt = props.movement.date_receipt
      }
    }
  )
</script>

<template>
  <Dialog :open="open" @update:open="value => $emit('close', value)">
    <DialogContent class="sm:max-w-[425px]">
      <ScrollArea class="h-full max-h-[600px] rounded-md">
        <form @submit.prevent="submit">
          <DialogHeader>
            <DialogTitle class="font-bold">
              {{ isEditing ? 'Editar Recebimento' : 'Novo Recebimento' }}
            </DialogTitle>
            <DialogDescription>
              {{
                isEditing
                  ? 'Atualize os dados do recebimento'
                  : 'Crie uma novo recebimento'
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
                @keypress="onlyNumbers"
              />
              <span v-if="form.errors.value" class="text-red-500 text-sm">
                {{ form.errors.value }}
              </span>
            </div>
            <div class="flex flex-col space-y-1.5">
              <Label for="dateBuy">Data</Label>
              <Input
                id="dateReceipt"
                placeholder="Data da compra DD/MM/YYYY"
                v-model="form.dateReceipt"
                :maxlength="10"
                @input="formatDateReceipt"
              />
              <span v-if="form.errors.dateReceipt" class="text-red-500 text-sm">
                {{ form.errors.dateReceipt }}
              </span>
            </div>
            <div class="flex flex-col space-y-1.5">
              <Label for="period">Período</Label>
              <Input
                id="period"
                placeholder="Período de pagamento MM/YYYY"
                v-model="form.period"
                :maxlength="7"
                @input="formatDatePeriod"
              />
              <span v-if="form.errors.period" class="text-red-500 text-sm">
                {{ form.errors.period }}
              </span>
            </div>
            <div v-if="!props.movement" class="flex flex-col space-y-1.5">
              <Label>Quantidade de lançamentos</Label>
              <Select v-model="form.quantity">
                <SelectTrigger class="w-full">
                  <SelectValue placeholder="Selecione um devedor" />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectItem :value="1">Apenas 1</SelectItem>
                    <SelectItem :value="2">...2</SelectItem>
                    <SelectItem :value="3">...3</SelectItem>
                    <SelectItem :value="4">...4</SelectItem>
                    <SelectItem :value="5">...5</SelectItem>
                    <SelectItem :value="6">...6</SelectItem>
                    <SelectItem :value="7">...7</SelectItem>
                    <SelectItem :value="8">...8</SelectItem>
                    <SelectItem :value="9">...9</SelectItem>
                    <SelectItem :value="10">...10</SelectItem>
                    <SelectItem :value="11">...11</SelectItem>
                    <SelectItem :value="12">...12</SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <span v-if="form.errors.memberID" class="text-red-500 text-sm">
                {{ form.errors.memberID }}
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
                class="resize-none w-[380px]"
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
      </ScrollArea>
    </DialogContent>
  </Dialog>
</template>
