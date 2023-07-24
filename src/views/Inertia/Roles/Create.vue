<script setup>
import { reactive } from 'vue'
import { router, Head, Link, useForm } from '@inertiajs/vue3';

import AdminLayout from '@layouts/AdminLayout.vue';
import AppHead from '@components/AppHead.vue';
import PorticoAlert from '@components/PorticoAlert.vue';
import InputError from '@components/InputError.vue'
import layoutDataProp, { toast } from '@properties/layoutData.js';
import { ucwords, info } from '@properties/helpers.js';
import PrimaryButton from '@components/PrimaryButton.vue'
import LinkButton from '@components/LinkButton.vue'
import CheckBoxList from '@components/CheckBoxList.vue'

const props = defineProps({
    abilities: Object
})

const layoutData = reactive(layoutDataProp)

const form = useForm({
    title: '',
    abilities: [],
});

const updateFormAbilities = (payload) => {
    form.abilities = payload
}

// send to CheckBoxList
provide('propAbilities', props.abilities)
provide('updateFormAbilities', updateFormAbilities) 

const submit = async (e) => {
    layoutData.toast = await toast('info', 'Saving...')

    await form.post(route('roles.store'), {
        onFinish: async (visit) => layoutData.toast = await toast('info', 'Done!', false)
    });
}

onMounted(() => {
    
})
</script>

<template>
    <AdminLayout :data="layoutData">
        
        <AppHead :title="`Create User Roles`" :meta="{ 'description': 'Portico is an advance PHP Laravel login and user management system' }"/>

        <template #header>
            
            <PorticoAlert />

            <h2 class="tw-font-bold tw-text-base tw-text-gray-800 tw-leading-tight tw-w-full ">
                Add New User role

                <PrimaryButton
                    :disabled="!form.abilities?.length"
                    @click="($event) => submit()"
                    class="tw-float-right tw-invisible md:tw-visible">
                    <VIcon icon="mdi-floppy-disk"></VIcon>
                    <span class="tw-text-xs">&nbsp;Save</span>
                </PrimaryButton>

                <LinkButton
                    type="text"
                    class="tw-hidden md:tw-inline tw-float-right"
                    :href="route('roles.index')">
                    <VIcon icon="mdi-arrow-left"></VIcon>
                    <span class="tw-text-xs"> Back</span>
                </LinkButton> 

            </h2>
            
        </template>

        <div class="py-12 tw-text-sm">
            <div class="mx-auto sm:px-6 lg:px-8 px-0">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-600">
                        <VCard class="">
                            
                            <VCardText>
                                <VForm @submit.prevent="submit" class="tw-p-1 md:tw-p-6">
                                    <VRow class="tw-pb-12">
                                        <VCol>
                                            <VTextField 
                                                v-model="form.title"
                                                label="Role Name"
                                                type="text"
                                                required 
                                            />

                                            <InputError class="mt-2" :message="form.errors.title" :sub="`mb-0`" />
                                            <InputError class="mt-2" :message="form.errors.abilities" :sub="`mb-0`" />

                                        </VCol>
                                    </VRow>

                                    <CheckBoxList />

                                    <div class="tw-text-center tw-pb-6">
                                        
                                        <PrimaryButton
                                            :disabled="!form.abilities?.length"
                                            @click="($event) => submit()"
                                            class="tw-visible md:tw-invisible tw-mb-6 "
                                            block>
                                            <VIcon icon="mdi-pencil"></VIcon>
                                            Save
                                        </PrimaryButton>

                                        <LinkButton
                                            :href="route('roles.index')"
                                            type="text"
                                            class="tw-block md:tw-hidden ">
                                            <VIcon icon="mdi-arrow-left"></VIcon>
                                            Back
                                        </LinkButton> 

                                    </div>

                                </VForm>
                            </VCardText>

                        </VCard>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>