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
    movement: {
      type: Object,
      required: false,
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
    quantity: 1,
    description: '',
    memberID: null,
    originID: null,
    categoryID: null,
  })
  const installment = ref('')

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
  const formatDateBuy = event => {
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
        form.description = props.movement.description ?? ''
        form.memberID = props.movement.member_id
        form.originID = props.movement.origin_id
        form.categoryID = props.movement.category_id
        form.dateBuy = props.movement.date_buy
        installment.value = props.movement.installment ?? ''
      }
    }
  )
</script>

<template>
  <Dialog :open="open" @update:open="value => $emit('close', value)">
    <DialogContent
      class="sm:max-w-[500px] max-w-[95vw] max-h-[90vh] overflow-hidden"
    >
      <ScrollArea class="h-full max-h-[calc(90vh-2rem)] rounded-md">
        <form @submit.prevent="submit" class="p-1">
          <DialogHeader class="px-4 pt-4">
            <DialogTitle class="font-bold text-lg sm:text-xl">
              {{ isEditing ? 'Editar Movimentação' : 'Nova Movimentação' }}
            </DialogTitle>
            <DialogDescription class="text-sm sm:text-base">
              {{
                isEditing
                  ? 'Atualize os dados da movimentação'
                  : 'Crie uma nova movimentação'
              }}
            </DialogDescription>
          </DialogHeader>

          <div class="grid gap-4 py-4 px-4">
            <!-- Valor -->
            <div class="flex flex-col space-y-1.5">
              <Label for="value" class="text-sm sm:text-base">Valor</Label>
              <Input
                id="value"
                placeholder="R$ Valor da movimentação"
                v-model="form.value"
                @keypress="onlyNumbers"
                class="w-full text-sm sm:text-base"
              />
              <span
                v-if="form.errors.value"
                class="text-red-500 text-xs sm:text-sm"
              >
                {{ form.errors.value }}
              </span>
            </div>

            <!-- Data -->
            <div class="flex flex-col space-y-1.5">
              <Label for="dateBuy" class="text-sm sm:text-base">Data</Label>
              <Input
                id="dateBuy"
                placeholder="Data da compra DD/MM/YYYY"
                v-model="form.dateBuy"
                :maxlength="10"
                @input="formatDateBuy"
                class="w-full text-sm sm:text-base"
              />
              <span
                v-if="form.errors.dateBuy"
                class="text-red-500 text-xs sm:text-sm"
              >
                {{ form.errors.dateBuy }}
              </span>
            </div>

            <!-- Período -->
            <div class="flex flex-col space-y-1.5">
              <Label for="period" class="text-sm sm:text-base">Período</Label>
              <Input
                id="period"
                placeholder="Período de pagamento MM/YYYY"
                v-model="form.period"
                :maxlength="7"
                @input="formatDatePeriod"
                class="w-full text-sm sm:text-base"
              />
              <span
                v-if="form.errors.period"
                class="text-red-500 text-xs sm:text-sm"
              >
                {{ form.errors.period }}
              </span>
            </div>

            <!-- Parcela (se editing) -->
            <div v-if="props.movement" class="flex flex-col space-y-1.5">
              <Label for="installment" class="text-sm sm:text-base"
                >Parcela</Label
              >
              <Input
                id="installment"
                :placeholder="installment.trim() === '' ? 'Sem informação' : ''"
                v-model="installment"
                disabled
                class="w-full text-sm sm:text-base"
              />
            </div>

            <!-- Quantidade de lançamentos (se novo) -->
            <div v-if="!props.movement" class="flex flex-col space-y-1.5">
              <Label class="text-sm sm:text-base"
                >Quantidade de lançamentos</Label
              >
              <Select v-model="form.quantity">
                <SelectTrigger class="w-full text-sm sm:text-base">
                  <SelectValue placeholder="Selecione a quantidade" />
                </SelectTrigger>
                <SelectContent class="max-h-60 overflow-y-auto">
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
            </div>

            <!-- Devedor -->
            <div class="flex flex-col space-y-1.5">
              <Label class="text-sm sm:text-base">Devedor</Label>
              <Select v-model="form.memberID">
                <SelectTrigger class="w-full text-sm sm:text-base">
                  <SelectValue placeholder="Selecione um devedor" />
                </SelectTrigger>
                <SelectContent class="max-h-60 overflow-y-auto">
                  <SelectGroup>
                    <SelectItem :value="null">Sem devedor</SelectItem>
                    <SelectItem
                      v-for="(item, index) in members"
                      :value="item.id"
                      :key="index"
                      class="text-sm sm:text-base"
                    >
                      {{ item.name }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <span
                v-if="form.errors.memberID"
                class="text-red-500 text-xs sm:text-sm"
              >
                {{ form.errors.memberID }}
              </span>
            </div>

            <!-- Origem -->
            <div class="flex flex-col space-y-1.5">
              <Label class="text-sm sm:text-base">Origem</Label>
              <Select v-model="form.originID">
                <SelectTrigger class="w-full text-sm sm:text-base">
                  <SelectValue placeholder="Selecione uma origem" />
                </SelectTrigger>
                <SelectContent class="max-h-60 overflow-y-auto">
                  <SelectGroup>
                    <SelectItem :value="null">Sem origem</SelectItem>
                    <SelectItem
                      v-for="(item, index) in origins"
                      :value="item.id"
                      :key="index"
                      class="text-sm sm:text-base"
                    >
                      {{ item.name }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <span
                v-if="form.errors.originID"
                class="text-red-500 text-xs sm:text-sm"
              >
                {{ form.errors.originID }}
              </span>
            </div>

            <!-- Categoria -->
            <div class="flex flex-col space-y-1.5">
              <Label class="text-sm sm:text-base">Categoria</Label>
              <Select v-model="form.categoryID">
                <SelectTrigger class="w-full text-sm sm:text-base">
                  <SelectValue placeholder="Selecione uma categoria" />
                </SelectTrigger>
                <SelectContent class="max-h-60 overflow-y-auto">
                  <SelectGroup>
                    <SelectItem :value="null">Sem categoria</SelectItem>
                    <SelectItem
                      v-for="(item, index) in categories"
                      :value="item.id"
                      :key="index"
                      class="text-sm sm:text-base"
                    >
                      {{ item.name }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <span
                v-if="form.errors.categoryID"
                class="text-red-500 text-xs sm:text-sm"
              >
                {{ form.errors.categoryID }}
              </span>
            </div>

            <!-- Descrição -->
            <div class="flex flex-col space-y-1.5">
              <Label for="description" class="text-sm sm:text-base"
                >Descrição</Label
              >
              <Textarea
                placeholder="Anote observações dessa movimentação"
                class="resize-none w-full min-h-[80px] text-sm sm:text-base"
                v-model="form.description"
              />
              <span
                v-if="form.errors.description"
                class="text-red-500 text-xs sm:text-sm"
              >
                {{ form.errors.description }}
              </span>
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
      </ScrollArea>
    </DialogContent>
  </Dialog>
</template>
