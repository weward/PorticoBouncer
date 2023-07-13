<script setup>
import { reactive } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PorticoAlert from '@/components/PorticoAlert.vue';
import { router, Head, Link } from '@inertiajs/vue3';
import layoutDataProp from '@/Properties/layoutData.js'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
    roles: Object,
    hasExistingAbility: Boolean,
})

const layoutData = reactive(layoutDataProp)

const gotoCreatePage = () => {
    router.get(route('roles.create'))
}

const viewRecord = (roleId) => {
    router.get(route('roles.show', { role: roleId }))
}

const editRecord = (roleId) => {
    router.get(route('roles.edit', { role: roleId }))
}


</script>

<template>
    <AdminLayout :data="layoutData">

        <Head title="User Roles" />

        <template #header>
            
            <PorticoAlert />

            <h2 class="tw-font-bold tw-text-base leading-tight tw-w-full">
                User Roles
                <PrimaryButton
                    v-if="hasExistingAbility"
                    @click="() => gotoCreatePage()"
                    class="tw-float-right">
                    <VIcon icon="mdi-plus"></VIcon>
                    <span class="tw-text-xs">&nbsp;New</span>
                </PrimaryButton>
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 px-0">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <VTable>
                            <thead>
                                <tr>
                                    <th class="text-uppercase">
                                        Title
                                    </th>
                                    <th class="text-center text-uppercase">
                                        Name
                                    </th>
                                    <th class="text-center text-uppercase"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role in roles.data" :key="role.id">
                                    <td>{{ role.title }}</td>
                                    <td class="text-center">
                                        {{ role.name }}
                                    </td>
                                    <td class="text-center">
                                        <VBtn @click="viewRecord(role.id)" icon="mdi-eye" color="primary" density="comfortable" variant="text">
                                            <VIcon icon="mdi-eye" class="text-lg"></VIcon>
                                            <VTooltip activator="parent" location="top">View</VTooltip>
                                        </VBtn>
                                        <VBtn @click="editRecord(role.id)" icon="mdi-pencil" color="primary"  density="comfortable" variant="text">
                                            <VIcon icon="mdi-pencil" class="text-lg"></VIcon>
                                            <VTooltip activator="parent" location="top">Edit</VTooltip>
                                        </VBtn>
                                    </td>
                                </tr>
                            </tbody>
                        </VTable>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>