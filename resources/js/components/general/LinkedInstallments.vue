<script setup>
  import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
  } from '@/components/ui/dialog'
  import { Button } from '@/components/ui/button'
  import { ScrollArea } from '@/components/ui/scroll-area'
  import { router } from '@inertiajs/vue3'
  import { ref, watch } from 'vue'
  import { toast } from 'vue-sonner'

  defineOptions({
    name: 'LinkedInstallments',
  })

  const props = defineProps({
    movement: {
      type: Object,
      required: false,
      default: null,
    },
    siblings: {
      type: Array,
      required: false,
      default: () => [],
    },
    open: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])

  const selectedIds = ref([])

  const formatCurrency = value => {
    if (!value) return 'R$ 0,00'
    return Number(value).toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    })
  }

  const toggle = id => {
    const index = selectedIds.value.indexOf(id)
    if (index === -1) {
      selectedIds.value.push(id)
    } else {
      selectedIds.value.splice(index, 1)
    }
  }

  const destroySelected = () => {
    if (selectedIds.value.length === 0) return

    const label =
      selectedIds.value.length === 1
        ? 'a parcela selecionada'
        : `as ${selectedIds.value.length} parcelas selecionadas`

    if (!confirm(`Tem certeza que deseja excluir ${label}?`)) return

    router.delete(
      route('data.destroyInstallments', { groupID: props.movement.group_id }),
      {
        data: { ids: selectedIds.value },
        onSuccess: () => {
          selectedIds.value = []
          emit('close', false)
          toast.success('Parcela(s) excluída(s)')
        },
      }
    )
  }

  watch(
    () => props.open,
    () => {
      selectedIds.value = []
    }
  )
</script>

<template>
  <Dialog :open="open" @update:open="value => $emit('close', value)">
    <DialogContent
      class="sm:max-w-[500px] max-w-[95vw] max-h-[90vh] overflow-hidden"
    >
      <ScrollArea class="h-full max-h-[calc(90vh-2rem)] rounded-md">
        <div class="p-1">
          <DialogHeader class="px-4 pt-4">
            <DialogTitle class="font-bold text-lg sm:text-xl"
              >Parcelas vinculadas</DialogTitle
            >
            <DialogDescription class="text-sm sm:text-base">
              Selecione as parcelas que deseja excluir
            </DialogDescription>
          </DialogHeader>

          <div class="px-4 py-4 space-y-2">
            <label
              v-for="item in props.siblings"
              :key="item.id"
              class="flex items-center gap-3 rounded-md border p-3 text-sm sm:text-base cursor-pointer"
              :class="item.id === props.movement?.id ? 'border-primary' : ''"
            >
              <input
                type="checkbox"
                class="h-4 w-4 shrink-0 rounded border-gray-300 accent-primary"
                :checked="selectedIds.includes(item.id)"
                @change="() => toggle(item.id)"
              />
              <div class="flex-1">
                <p class="font-medium">
                  {{ item.installment ?? 'Sem parcela' }} —
                  {{ formatCurrency(item.value) }}
                </p>
                <p class="text-xs sm:text-sm text-muted-foreground">
                  {{ item.date_buy }} · Período {{ item.period }}
                </p>
              </div>
            </label>
          </div>

          <DialogFooter class="px-4 pb-4">
            <Button
              variant="destructive"
              class="w-full text-sm sm:text-base py-2"
              :disabled="selectedIds.length === 0"
              @click="destroySelected"
            >
              Excluir selecionadas ({{ selectedIds.length }})
            </Button>
          </DialogFooter>
        </div>
      </ScrollArea>
    </DialogContent>
  </Dialog>
</template>
