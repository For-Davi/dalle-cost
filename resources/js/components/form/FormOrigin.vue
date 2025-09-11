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
  import { Label } from '@/components/ui/label'
  import { useForm } from '@inertiajs/vue3'
  import { ref, watch, computed } from 'vue'
  import { toast } from 'vue-sonner'

  defineOptions({
    name: 'FormOrigin',
  })

  const props = defineProps({
    origin: {
      type: Object,
      required: false,
      default: null,
    },
    members: {
      type: Array,
      required: false,
      default: [],
    },
    open: {
      type: Boolean,
      default: false,
    },
  })

  const emit = defineEmits(['close'])

  const form = useForm({
    name: '',
    payday: '',
    memberID: null,
  })

  const isEditing = computed(() => !!props.origin)

  const formatPayday = event => {
    let value = event.target.value.replace(/\D/g, '')
  }
  const submit = () => {
    if (isEditing.value) {
      form.put(route('origin.update', { originID: props.origin.id }), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Origem atualizada')
        },
      })
    } else {
      form.post(route('origin.store'), {
        onSuccess: () => {
          form.reset()
          emit('close', false)
          toast.success('Origem criada')
        },
      })
    }
  }

  watch(
    () => props.open,
    newVal => {
      form.reset()
      if (props.origin) {
        form.name = props.origin.name
        form.payday = props.origin.payday
        form.memberID = props.origin.member_id
      }
    }
  )
</script>

<template>
  <Dialog :open="open" @update:open="value => $emit('close', value)">
    <DialogContent
      class="sm:max-w-[450px] max-w-[95vw] max-h-[90vh] overflow-hidden"
    >
      <form @submit.prevent="submit" class="p-1">
        <DialogHeader class="px-4 pt-4">
          <DialogTitle class="font-bold text-lg sm:text-xl">
            {{ isEditing ? 'Editar Origem' : 'Nova Origem' }}
          </DialogTitle>
          <DialogDescription class="text-sm sm:text-base">
            {{
              isEditing ? 'Atualize os dados da origem' : 'Crie uma nova origem'
            }}
          </DialogDescription>
        </DialogHeader>

        <div class="grid gap-4 py-4 px-4">
          <!-- Nome -->
          <div class="flex flex-col space-y-1.5">
            <Label for="name" class="text-sm sm:text-base">Nome</Label>
            <Input
              id="name"
              placeholder="Nome da origem"
              v-model="form.name"
              class="w-full text-sm sm:text-base"
              :disabled="form.processing"
            />
            <span
              v-if="form.errors.name"
              class="text-red-500 text-xs sm:text-sm"
            >
              {{ form.errors.name }}
            </span>
          </div>

          <!-- Pagamento -->
          <div class="flex flex-col space-y-1.5">
            <Label for="payday" class="text-sm sm:text-base">Pagamento</Label>
            <Input
              id="payday"
              placeholder="Dia de pagamento"
              v-model="form.payday"
              @input="formatPayday"
              maxlength="2"
              class="w-full text-sm sm:text-base"
              :disabled="form.processing"
            />
            <span
              v-if="form.errors.payday"
              class="text-red-500 text-xs sm:text-sm"
            >
              {{ form.errors.payday }}
            </span>
          </div>

          <!-- Responsável -->
          <div class="flex flex-col space-y-1.5">
            <Label class="text-sm sm:text-base">Responsável pela origem</Label>
            <Select v-model="form.memberID" :disabled="form.processing">
              <SelectTrigger class="w-full text-sm sm:text-base">
                <SelectValue placeholder="Selecione um responsável" />
              </SelectTrigger>
              <SelectContent class="max-h-60 overflow-y-auto">
                <SelectGroup>
                  <SelectItem :value="null" class="text-sm sm:text-base">
                    Sem responsável
                  </SelectItem>
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
              v-if="form.errors.role"
              class="text-red-500 text-xs sm:text-sm"
            >
              {{ form.errors.role }}
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
    </DialogContent>
  </Dialog>
</template>
