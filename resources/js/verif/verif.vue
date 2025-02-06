<template>
    <!--begin::Authentication Layout -->
    <div class="d-flex flex-column flex-column-fluid flex-lg-row justify-content-center"
        :style="`background-image: url('${setting?.bg_auth}'); background-size: cover`">
        <!--begin::Body-->
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
            <!--begin::Card-->
            <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-md-20 w-100">
                <!--begin::Wrapper-->
                <div class="d-flex flex-center flex-column flex-column-fluid px-10 py-20 py-md-0">
                    <template v-if="currentView === 'verification'">
                        <!-- Email Verification Form -->
                        <div>
                            <!--begin::Card header-->
                            <div class="card-header text-center mb-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark mb-2">{{ showOTPInput ? 'Verifikasi OTP' : 'Verifikasi Email' }}</span>
                                </h3>
                                <span class="text-gray-400 my-2 fw-semibold">
                                    {{ showOTPInput ? 'Masukkan kode OTP yang telah dikirim ke email Anda' : 'Tolong verifikasi email anda sebelum melakukan peminjaman' }}
                                </span>
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body">
                                <!-- Email Form -->
                                <div v-if="!showOTPInput">
                                    <VForm class="form w-100" @submit="sendOTP" :validation-schema="verificationSchema">
                                        <div class="fv-row mb-10">
                                            <label class="form-label fs-6 fw-bold required">Nama</label>
                                            <Field tabindex="1" 
                                                class="form-control form-control-lg form-control-solid"
                                                type="text" 
                                                name="name" 
                                                autocomplete="off" 
                                                v-model="formData.name" />
                                            <div class="fv-plugins-message-container">
                                                <div class="fv-help-block">
                                                    <ErrorMessage name="name" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="fv-row mb-10">
                                            <label class="form-label fs-6 fw-bold required">Email</label>
                                            <Field tabindex="2" 
                                                class="form-control form-control-lg form-control-solid"
                                                type="email" 
                                                name="email" 
                                                autocomplete="off" 
                                                v-model="formData.email" />
                                            <div class="fv-plugins-message-container">
                                                <div class="fv-help-block">
                                                    <ErrorMessage name="email" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">   
                                            <button tabindex="3" 
                                                    type="submit" 
                                                    ref="submitButton"
                                                    class="btn btn-lg btn-primary w-100 mb-5">
                                                <span class="indicator-label">Kirim OTP</span>
                                                <span class="indicator-progress">
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </VForm>
                                </div>

                                <!-- OTP Verification Form -->
                                <div v-else>
                                    <VForm class="form w-100" @submit="verifyOTP" :validation-schema="otpSchema">
                                        <div class="fv-row mb-10">
                                            <label class="form-label fs-6 fw-bold required">Kode OTP</label>
                                            <Field tabindex="1" 
                                                class="form-control form-control-lg form-control-solid"
                                                type="text" 
                                                name="otp" 
                                                maxlength="6"
                                                autocomplete="off" 
                                                v-model="otpCode" />
                                            <div class="fv-plugins-message-container">
                                                <div class="fv-help-block">
                                                    <ErrorMessage name="otp" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" 
                                                    class="btn btn-lg btn-primary w-100 mb-3">
                                                <span class="indicator-label">Verifikasi</span>
                                                <span class="indicator-progress">
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>

                                            <button type="button" 
                                                    class="btn btn-lg btn-light w-100"
                                                    @click="resendOTP"
                                                    :disabled="isLoading || resendCountdown > 0">
                                                {{ resendCountdown > 0 ? `Kirim ulang dalam ${resendCountdown}s` : 'Kirim Ulang OTP' }}
                                            </button>
                                        </div>
                                    </VForm>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                    </template>
                    <router-view v-else></router-view>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication Layout -->
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { Form as VForm, Field, ErrorMessage } from "vee-validate";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { getAssetPath } from "@/core/helpers/assets";
import LayoutService from "@/core/services/LayoutService";
import { useBodyStore } from "@/stores/body";
import { useSetting } from "@/services";

export default defineComponent({
    name: "auth-layout",
    components: {
        VForm,
        Field,
        ErrorMessage
    },
    setup() {
        const store = useBodyStore();
        const { data: setting = {} } = useSetting();
        const route = useRoute();
        const router = useRouter();
        
        // Verification state
        const currentView = ref('verification');
        const showOTPInput = ref(false);
        const isLoading = ref(false);
        const resendCountdown = ref(0);
        const sessionToken = ref('');
        const otpCode = ref('');

        // Form data
        const formData = ref({
            name: '',
            email: '',
            item_uuid: route.params.uuid
        });

        // Validation schemas
        const verificationSchema = Yup.object().shape({
            name: Yup.string()
                .required('Nama wajib diisi')
                .min(2, 'Nama minimal 2 karakter'),
            email: Yup.string()
                .email('Format email tidak valid')
                .required('Email wajib diisi')
        });

        const otpSchema = Yup.object().shape({
            otp: Yup.string()
                .required('Kode OTP wajib diisi')
                .length(6, 'Kode OTP harus 6 digit')
                .matches(/^\d+$/, 'Kode OTP harus berupa angka')
        });

        // Send OTP function
        const sendOTP = async () => {
            try {
                isLoading.value = true;
                const response = await axios.post('/peminjaman/send-otp', {
                    ...formData.value,
                    item_uuid: route.params.uuid
                });
                
                if (response.data.status) {
                    toast.success(response.data.message);
                    sessionToken.value = response.data.session_token;
                    showOTPInput.value = true;
                    startResendCountdown();
                }
            } catch (error) {
                toast.error(error.response?.data?.message || 'Terjadi kesalahan saat mengirim OTP');
            } finally {
                isLoading.value = false;
            }
        };

        // Verify OTP function
        const verifyOTP = async () => {
            try {
                isLoading.value = true;
                const response = await axios.post('/peminjaman/verify-otp', {
                    session_token: sessionToken.value,
                    otp: otpCode.value
                });

                if (response.data.status) {
                    toast.success(response.data.message);
                    localStorage.setItem('verificationData', JSON.stringify({
                        name: response.data.data.name,
                        email: response.data.data.email,
                        session_token: sessionToken.value
                    }));
                    router.push(`/form/${route.params.uuid}`);
                }
            } catch (error) {
                toast.error(error.response?.data?.message || 'Terjadi kesalahan saat verifikasi OTP');
            } finally {
                isLoading.value = false;
            }
        };

        // Resend countdown timer
        const startResendCountdown = () => {
            resendCountdown.value = 60;
            const timer = setInterval(() => {
                if (resendCountdown.value > 0) {
                    resendCountdown.value--;
                } else {
                    clearInterval(timer);
                }
            }, 1000);
        };

        // Resend OTP function
        const resendOTP = () => {
            if (resendCountdown.value === 0) {
                sendOTP();
            }
        };

        onMounted(() => {
            LayoutService.emptyElementClassesAndAttributes(document.body);
            store.addBodyClassname("app-blank");
            store.addBodyClassname("bg-body");

            // Check if verification is already done
            const verificationData = localStorage.getItem('verificationData');
            if (verificationData) {
                router.push(`/form/${route.params.uuid}`);
            }
        });

        return {
            getAssetPath,
            setting,
            currentView,
            showOTPInput,
            isLoading,
            resendCountdown,
            formData,
            otpCode,
            verificationSchema,
            otpSchema,
            sendOTP,
            verifyOTP,
            resendOTP
        };
    },
});
</script>   