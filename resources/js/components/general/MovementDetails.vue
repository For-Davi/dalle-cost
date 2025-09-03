<script setup>
  import { Dialog, DialogContent } from '@/components/ui/dialog'
  import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
  import { ClipboardType } from 'lucide-vue-next'
  import { ScrollArea } from '@/components/ui/scroll-area'

  defineOptions({
    name: 'MovementDetails',
  })

  const props = defineProps({
    movement: {
      type: Object,
      required: true,
      default: null,
    },
    open: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])

  const formatCurrency = value => {
    if (!value) return 'R$ 0,00'
    return Number(value).toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    })
  }
</script>

<template>
  <Dialog :open="open" @update:open="value => $emit('close', value)">
    <DialogContent class="sm:max-w-[450px]">
      <ScrollArea class="h-full max-h-[500px] rounded-md">
        <div class="space-y-2 text-sm">
          <p>
            <span class="font-bold">Valor:</span>
            {{ formatCurrency(props.movement?.value) }}
          </p>
          <p>
            <span class="font-bold">Data da Compra:</span>
            {{ props.movement?.date_buy }}
          </p>
          <p>
            <span class="font-bold">Período:</span> {{ props.movement?.period }}
          </p>
          <p>
            <span class="font-bold">Membro:</span>
            {{ props.movement?.member?.name ?? '—' }}
          </p>
          <p>
            <span class="font-bold">Origem:</span>
            {{ props.movement?.origin?.name ?? '—' }}
          </p>
          <p>
            <span class="font-bold">Categoria:</span>
            {{ props.movement?.category?.name ?? '—' }}
          </p>
          <p>
            <span class="font-bold">Descrição:</span>
            {{ props.movement?.description ?? '—' }}
          </p>
        </div>
      </ScrollArea>
    </DialogContent>
  </Dialog>
</template>
