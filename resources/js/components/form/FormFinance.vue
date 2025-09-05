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
  import { Input } from '@/components/ui/input'
  import { Label } from '@/components/ui/label'
  import { useForm } from '@inertiajs/vue3'
  import { ref, watch, computed } from 'vue'
  import { toast } from 'vue-sonner'

  defineOptions({
    name: 'FormFinance',
  })

  const props = defineProps({
    finance: {
      type: Object,
      required: false,
      default: null,
    },
    open: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])

  const form = useForm({
    period: '',
    value: '',
  })

  const isEditing = computed(() => !!props.finance)

  const submit = () => {
    const options = {
      onSuccess: () => {
        form.reset()
        emit('close', false)
        toast.success(isEditing.value ? 'Finança atualizada' : 'Finança criada')
      },
      onError: errors => {
        Object.values(errors)
          .flat()
          .forEach(message => {
            toast.error(message)
          })
      },
    }

    if (isEditing.value) {
      form.put(
        route('finance.update', { financeID: props.finance.id }),
        options
      )
    } else {
      form.post(route('finance.store'), options)
    }
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
      if (props.finance) {
        form.period = props.finance.period
        form.value = props.finance.value
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
            {{ isEditing ? 'Editar Finança' : 'Nova Finança' }}
          </DialogTitle>
          <DialogDescription>
            {{
              isEditing
                ? 'Atualize os dados da finança'
                : 'Crie uma nova finança'
            }}
          </DialogDescription>
        </DialogHeader>
        <div class="grid gap-4 py-4">
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
          <div class="flex flex-col space-y-1.5">
            <Label for="value">Valor</Label>
            <Input
              id="value"
              placeholder="R$ Valor da finança"
              v-model="form.value"
              @keypress="onlyNumbers"
            />
            <span v-if="form.errors.value" class="text-red-500 text-sm">
              {{ form.errors.value }}
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
