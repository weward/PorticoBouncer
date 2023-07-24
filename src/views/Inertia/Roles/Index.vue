<script setup>
import { reactive, computed } from 'vue'
import AdminLayout from '@layouts/AdminLayout.vue';
import PorticoAlert from '@components/PorticoAlert.vue';
import { router, Head, Link, useForm } from '@inertiajs/vue3';
import layoutDataProp from '@properties/layoutData.js'
import PrimaryButton from '@components/PrimaryButton.vue'
import SecondaryButton from '@components/SecondaryButton.vue'
import FilterForm from '@components/FilterForm.vue'

const props = defineProps({
    roles: Object,
    hasExistingAbility: Boolean,
})

const layoutData = reactive(layoutDataProp)

const data = reactive({
    toggleFilterForm: false
})

const filterForm = useForm({
    title: '',
})

const gotoCreatePage = () => {
    router.get(route('roles.create'))
}

const viewRecord = (roleId) => {
    router.get(route('roles.show', { role: roleId }))
}

const editRecord = (roleId) => {
    router.get(route('roles.edit', { role: roleId }))
}

const updateLayoutData = (payload) => {
    layoutData.toast = payload
}

const toggleFilterForm = () => {
    data.toggleFilterForm = !data.toggleFilterForm
}

const filterToolTip = computed(() => {
    return data.toggleFilterForm ? 'Hide filter form' : 'Display filter form'
})

provide('updateLayoutData', updateLayoutData)
provide('layoutDataProvide', layoutData)
provide('filterForm', filterForm)



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

                 <SecondaryButton
                    v-if="hasExistingAbility"
                    @click="() => toggleFilterForm()"
                    class="tw-float-right tw-mr-3 tw-bg-gray-300 hover:tw-bg-gray-100">
                    <VIcon v-if="!data.toggleFilterForm" icon="mdi-filter-variant"></VIcon>
                    <VIcon v-if="data.toggleFilterForm" icon="mdi-close-outline"></VIcon>
                    <VTooltip activator="parent" location="top" :text="filterToolTip"></VTooltip>
                    <span class="tw-text-xs"></span>
                </SecondaryButton>
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 px-0">
                
                <FilterForm 
                    v-if="data.toggleFilterForm"
                    :url="route('roles.index')"
                >
                    
                    <VRow class="tw-pb-12">
                        <VCol>
                            <VTextField 
                                v-model="filterForm.title"
                                label="Role Title"
                                type="text"
                                required 
                            />

                            <InputError class="mt-2" :message="filterForm.errors.title" :sub="`mb-0`" />

                        </VCol>
                    </VRow>

                </FilterForm>

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