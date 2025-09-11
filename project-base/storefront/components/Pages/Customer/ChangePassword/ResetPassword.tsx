import { Button } from 'components/Forms/Button/Button';
import { usePasswordRecoveryMutation } from 'graphql/requests/passwordRecovery/mutations/PasswordRecoveryMutation.generated';
import { GtmFormType } from 'gtm/enums/GtmFormType';
import { onGtmSendFormEventHandler } from 'gtm/handlers/onGtmSendFormEventHandler';
import useTranslation from 'next-translate/useTranslation';
import { showSuccessMessage } from 'utils/toasts/showSuccessMessage';

type ResetPasswordProps = {
    email: string;
};

export const ResetPassword: FC<ResetPasswordProps> = ({ email }) => {
    const { t } = useTranslation();
    const [, resetPassword] = usePasswordRecoveryMutation();

    const onResetPasswordHandler = async () => {
        const resetPasswordResult = await resetPassword({ email: email });

        if (resetPasswordResult.data?.RequestPasswordRecovery !== undefined) {
            showSuccessMessage(t('We sent an email with further steps to your address'));
            onGtmSendFormEventHandler(GtmFormType.forgotten_password);
        }
    };

    return (
        <Button size="small" onClick={onResetPasswordHandler}>
            {t('Send me a link to set a new password')}
        </Button>
    );
};
