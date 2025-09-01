<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import Button from '@/components/ui/button/Button.vue'
  import FormUser from '@/components/form/FormUser.vue'

  defineOptions({
    name: 'Users',
  })
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Dados</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormUser />
      </section>
      <section>table</section>
    </div>
  </PanelLayout>
</template>
