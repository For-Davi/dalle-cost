<script setup>
  import { Dialog, DialogContent } from '@/components/ui/dialog'
  import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
  import { ClipboardType } from 'lucide-vue-next'

  defineOptions({
    name: 'MovementDetails',
  })

  const props = defineProps({
    movement: {
      type: Object | null,
      required: true,
      default: null,
    },
    open: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])
</script>

<template>
  <Dialog :open="open" @update:open="value => $emit('close', value)">
    <DialogContent class="sm:max-w-[425px]">
      <Alert>
        <ClipboardType class="h-4 w-4" />
        <AlertTitle>Descrição</AlertTitle>
        <AlertDescription>
          {{ props.description ?? '' }}
        </AlertDescription>
      </Alert>
    </DialogContent>
  </Dialog>
</template>
