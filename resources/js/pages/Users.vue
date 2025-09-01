<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import Button from '@/components/ui/button/Button.vue'
  import FormUser from '@/components/form/FormUser.vue'
  import { router } from '@inertiajs/vue3'
  import { onMounted } from 'vue'

  defineOptions({
    name: 'Users',
  })

  const props = defineProps({
    users: {
      type: Array,
      required: true,
    },
  })
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Usuários</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormUser />
      </section>
      <section>{{ users }}</section>
    </div>
  </PanelLayout>
</template>
