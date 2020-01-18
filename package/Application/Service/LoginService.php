<?php
declare(strict_types=1);

namespace package\Application\Service;

use InvalidArgumentException;
use package\Application\Model\ValueObject\ValidationError;
use package\Presentation\LoginPagePresenter;
use package\Presentation\LoginPresenter;

final class LoginService
{
    public function __construct(
        Authenticator $authenticator,
        LoginValidator $validator,
        LoginPagePresenter $loginPagePresenter,
        LoginPresenter $loginPresenter)
    {
        $this->authenticator = $authenticator;
        $this->validator = $validator;
        $this->loginPagePresenter = $loginPagePresenter;
        $this->loginPresenter = $loginPresenter;
    }

    public function handle(LoginRequest $request): void
    {
        $errors = $request->validate($this->validator);
        if (count($errors) > 0) {
            $this->loginPagePresenter->output($errors, $request->rawValues());
            return;
        }

        try {
            if (!$this->authenticator->login($request->name(), $request->password())) {
                $errors[] = new ValidationError('top', 'アカウント名、またはパスワードに誤りがあります。');
                $this->loginPagePresenter->output($errors, $request->rawValues());
                return;
            }
        } catch (InvalidArgumentException $exception) {
            // 変なアカウント名やパスワードも例外にしない
            $errors[] = new ValidationError('top', 'アカウント名、またはパスワードに誤りがあります。');
            $this->loginPagePresenter->output($errors, $request->rawValues());
            return;
        }

        $this->loginPresenter->output();
    }

    private $authenticator;
    private $validator;
    private $loginPagePresenter;
    private $loginPresenter;
}
