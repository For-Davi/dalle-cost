<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import Button from '@/components/ui/button/Button.vue'
  import FormIntegral from '@/components/form/FormIntegral.vue'
  import FormRegister from '@/components/form/FormRegister.vue'
  import FormOrigin from '@/components/form/FormOrigin.vue'

  defineOptions({
    name: 'Data',
  })
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Dados</h1>
      <section class="bg-white p-4 rounded-lg shadow flex justify-end gap-2">
        <FormOrigin />
        <FormIntegral />
        <FormRegister />
      </section>
      <section>table</section>
    </div>
  </PanelLayout>
</template>
