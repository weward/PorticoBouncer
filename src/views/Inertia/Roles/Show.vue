<script setup>
import { reactive } from 'vue'
import AdminLayout from '@layouts/AdminLayout.vue'
import { router, Head, Link } from '@inertiajs/vue3'
import layoutDataProp from '@properties/layoutData.js'
import { ucwords } from '@properties/helpers.js'
import LinkButton from '@components/LinkButton.vue'

const props = defineProps({
    role: Object,
})

const layoutData = reactive(layoutDataProp)

</script>

<template>
    <AdminLayout :data="layoutData">

            <Head title="User Roles" />

            <template #header>
                <h2 class="tw-font-bold tw-text-base leading-tight tw-w-full">
                    <p class="tw-inline-block tw-pt-2">{{ role.title }} - User Role</p>
                    <LinkButton
                        type="primary"
                        :href="route('roles.edit', role.id)"
                        class="tw-float-right">
                        <VIcon icon="mdi-pencil"></VIcon>
                        <span class="tw-text-xs"> Edit</span>
                    </LinkButton>
                    <LinkButton
                        type="text"
                        class="tw-hidden md:tw-inline tw-float-right"
                        :href="route('roles.index')">
                        <VIcon icon="mdi-arrow-left"></VIcon>
                        <span class="tw-text-xs"> Back</span>
                    </LinkButton> 
                </h2>
            </template>


            <div class="md:tw-py-12 tw-pt-6 tw-pb-6 tw-text-sm ">
                <div class="mx-auto sm:px-6 lg:px-8 px-0">

                    <VCard class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                        <VCardTitle>
                            <div class="tw-py-3 tw-text-sm">
                                <VIcon icon="mdi-information-outline"></VIcon> 
                                <span class=" tw-font-bold"> About</span>
                            </div>
                        </VCardTitle>

                        <hr class="tw-border tw-border-b-purple-500">

                        <VCardItem class="tw-p-0">
                            
                            <VTable class="table-about--plain ">
                                <tbody>
                                    <tr>
                                        <td>Title</td>
                                        <td>{{ ucwords(role.title) }}</td>
                                        <td>Name</td>
                                        <td>{{ role.name }}</td>
                                    </tr>
                                </tbody>
                            </VTable>

                        </VCardItem>

                    </VCard>
                </div>
            </div>


            <div class="md:tw-py-6 tw-text-sm">
                <div class="mx-auto sm:px-6 lg:px-8 px-0">

                    <VCard class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        
                        <VCardTitle>
                            <div class="tw-py-3 tw-text-sm">
                                <VIcon icon="mdi-format-list-bulleted-square"></VIcon> 
                                <span class="tw-font-bold"> List of Abilities</span>
                            </div>
                        </VCardTitle>

                        <hr class="tw-border tw-border-b-purple-500">
                        
                        <VCardItem>
                            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-2">
                                <div v-for="ability, roleGroup in role.roleAbilities">
                                    
                                    <h3 class="tw-p-6 tw-pb-3 tw-font-semibold">
                                        {{ ucwords(roleGroup) }}
                                    </h3>

                                    <ul class="tw-pb-6 tw-pl-6 tw-text-gray-600">
                                        <li v-for="a in ability" :key="a.name" class="tw-pb-2">
                                            <VIcon icon="mdi-check" class="tw-text-green-600"></VIcon>
                                            {{ a.title }}
                                        </li>
                                    </ul>

                                </div>
                            </div>
                            
                        </VCardItem>

                    </VCard>
                </div>
            </div>

    </AdminLayout>
</template>