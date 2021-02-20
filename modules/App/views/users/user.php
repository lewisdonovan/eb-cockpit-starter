<?php

if (!isset($user['twofa'])) {

    $user['twofa'] = [
        'enabled' => false,
        'secret' => $this->helper('twfa')->createSecret(160)
    ];
}

?>
<kiss-container class="kiss-margin-large" size="small">

    <vue-view>
        <template>

            <?php if (!$isAccountView): ?>
            <h1 class="kiss-margin-large-bottom">
                <span v-if="!user._id"><?=t('Create user')?></span>
                <span v-if="user._id"><?=t('Edit user')?></span>
            </h1>
            <?php endif ?>

            <div class="kiss-flex kiss-flex-middle kiss-has-transition" v-if="user._id" :class="{'kiss-inactive': !user.active}">
                <div>
                    <app-avatar size="50" :name="user.name"></app-avatar>
                </div>
                <div class="kiss-margin-left kiss-flex-1">
                    <div class="kiss-text-bold">{{user.name}}</div>
                    <div class="kiss-color-muted kiss-size-small"><span class="kiss-color-primary">{{user.user}}</span> &bullet; {{user.email}}</div>
                </div>
            </div>

            <hr class="kiss-margin-large" v-if="user._id">

            <form :class="{'kiss-disabled':saving}" @submit.prevent="save">

                <?php if (!isset($user['_id']) || $user['_id'] != $this['user/_id']):?>
                <div class="kiss-margin">
                    <label><?=t('Active')?></label>
                    <field-boolean class="kiss-size-3" v-model="user.active"></field-boolean>
                </div>
                <?php endif ?>

                <div class="kiss-margin">
                    <label><?=t('Name')?></label>
                    <input class="kiss-input" type="text" v-model="user.name" required>
                </div>

                <div class="kiss-margin">
                    <label><?=t('User')?></label>
                    <input class="kiss-input" type="text" v-model="user.user" required>
                </div>

                <div class="kiss-margin">
                    <label><?=t('Email')?></label>
                    <input class="kiss-input" type="email" v-model="user.email" required>
                </div>

                <div class="kiss-margin">
                    <label><?=t('Password')?></label>
                    <input class="kiss-input" type="password" v-model="user.password" :placeholder="user._id ? '<?=t('Keep current password')?>':''" :required="!user._id" autocomplete="off">
                </div>

                <?php if (!isset($user['_id']) || $user['_id'] != $this['user/_id']):?>
                <div class="kiss-margin-large">
                    <label><?=t('Role')?></label>
                    <select class="kiss-select kiss-input" v-model="user.role" required>
                        <option value=""></option>
                        <option :value="role.appid" v-for="role in roles">{{ role.name }}</option>
                    </select>
                </div>
                <?php endif ?>

                <kiss-card class="kiss-margin kiss-margin-large-top kiss-padding" theme="bordered">
                    <label><?=t('API Key')?></label>
                    <div class="kiss-flex kiss-flex-middle">
                        <div class="kiss-flex-1 kiss-margin-small-right kiss-text-truncate kiss-disabled">
                            <span class="kiss-text-caption" v-if="!user.apiKey"><?=t('No api key created yet')?></span>
                            <span class="kiss-text-monospace kiss-text-bold" v-if="user.apiKey">{{ user.apiKey }}</span>
                        </div>
                        <a @click="generateToken"><icon>refresh</icon></a>
                        <a class="kiss-margin-small-left" v-if="user.apiKey" @click="copyToken"><icon>content_copy</icon></a>
                    </div>
                </kiss-card>

                <kiss-card class="kiss-margin kiss-padding" theme="bordered">
                    <label><?=t('Two-factor authentication (2FA)')?></label>
                    <div class="kiss-margin-small-top">
                        <field-boolean class="kiss-size-3" v-model="user.twofa.enabled"></field-boolean>
                    </div>
                    <kiss-row class="kiss-margin animated fadeIn" v-if="user.twofa.enabled">
                        <div><img src="<?=$this->route("/users/getSecretQRCode/{$user['twofa']['secret']}/150")?>" width="150" height="150" loading="lazy" style="background:#fff;border:10px #fff solid;"></div>
                        <div class="kiss-flex-1">

                            <p class="kiss-text-caption">
                                Scan the QR code with your 2FA mobile app<br>
                                or enter your secret manually:
                            </p>

                            <div class="kiss-margin kiss-text-monospace kiss-text-bold kiss-color-primary">
                                <?=$user['twofa']['secret']?>
                            </div>
                        </div>
                    </kiss-row>

                </kiss-card>

                <app-actionbar>

                    <kiss-container size="small">
                        <div class="kiss-flex kiss-flex-middle kiss-flex-right">
                            <div class="kiss-button-group">
                                <?php if (!$isAccountView && _allowed('app.users.manage')): ?>
                                <a class="kiss-button kiss-button-link" href="<?=$this->route('/users')?>">
                                    <span v-if="!user._id"><?=t('Cancel')?></span>
                                    <span v-if="user._id"><?=t('Close')?></span>
                                </a>
                                <?php endif ?>
                                <button type="submit" class="kiss-button kiss-button-primary">
                                    <span v-if="!user._id"><?=t('Create')?></span>
                                    <span v-if="user._id"><?=t('Update')?></span>
                                </button>
                            </div>
                        </div>
                    </kiss-container>

                </app-actionbar>

            </form>

        </template>

        <script type="module">

            export default {
                data() {
                    return {
                        saving: false,
                        user: <?=json_encode($user)?>,
                        roles: <?=json_encode($this->helper('acl')->roles())?>
                    };
                },

                methods: {

                    generateToken() {

                        this.$request('/utils/generateToken').then(res => {
                            this.user.apiKey = `USR-${res.token}`;
                        });
                    },

                    copyToken() {
                        App.utils.copyText(this.user.apiKey, () => {
                            App.ui.notify('Token copied!');
                        });
                    },

                    save() {

                        let isUpdate = this.user._id;

                        this.saving = true;

                        this.$request('/users/save', {user: this.user}).then(user => {
                            this.user = user;
                            this.saving = false;

                            if (isUpdate) {
                                App.ui.notify('User updated!');
                            } else {
                                App.ui.notify('User created!');
                            }
                        }).catch(res => {
                            this.saving = false;
                            App.ui.notify('Saving failed!', 'error');
                        })

                    }
                }
            }
        </script>

    </vue-view>

</kiss-container>