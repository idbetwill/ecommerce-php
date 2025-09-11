import { yupResolver } from '@hookform/resolvers/yup';
import { validateNewPassword, validateNewPasswordConfirm, validateOldPassword } from 'components/Forms/validationRules';
import useTranslation from 'next-translate/useTranslation';
import { useMemo } from 'react';
import { UseFormReturn } from 'react-hook-form';
import { ChangePasswordFormType } from 'types/form';
import { useShopsysForm } from 'utils/forms/useShopsysForm';
import * as Yup from 'yup';

export const useChangePasswordForm = (
    defaultValues: ChangePasswordFormType,
): [UseFormReturn<ChangePasswordFormType>, ChangePasswordFormType] => {
    const { t } = useTranslation();

    const resolver = yupResolver(
        Yup.object().shape<Record<keyof ChangePasswordFormType, any>>({
            oldPassword: validateOldPassword(t),
            newPassword: validateNewPassword(t),
            newPasswordConfirm: validateNewPasswordConfirm(t),
        }),
    );

    return [useShopsysForm(resolver, defaultValues), defaultValues];
};

type ChangePasswordFormMetaType = {
    formName: string;
    messages: {
        error: string;
        success: string;
    };
    fields: {
        [key in keyof ChangePasswordFormType]: {
            name: key;
            label: string;
            errorMessage?: string;
        };
    };
};

export const useChangePasswordFormMeta = (
    formProviderMethods: UseFormReturn<ChangePasswordFormType>,
): ChangePasswordFormMetaType => {
    const { t } = useTranslation();
    const errors = formProviderMethods.formState.errors;

    const formMeta = useMemo(
        () => ({
            formName: 'customer-change-password-form',
            messages: {
                error: t('An error occurred while changing your password'),
                success: t('Your password has been changed successfully'),
            },
            fields: {
                oldPassword: {
                    name: 'oldPassword' as const,
                    label: t('Current password'),
                    errorMessage: errors.oldPassword?.message,
                },
                newPassword: {
                    name: 'newPassword' as const,
                    label: t('New password'),
                    errorMessage: errors.newPassword?.message,
                },
                newPasswordConfirm: {
                    name: 'newPasswordConfirm' as const,
                    label: t('New password again'),
                    errorMessage: errors.newPasswordConfirm?.message,
                },
            },
        }),
        [errors.oldPassword?.message, errors.newPassword?.message, errors.newPasswordConfirm?.message, t],
    );
    return formMeta;
};
