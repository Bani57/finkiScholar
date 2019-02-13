<template>
    <form class="ui form" style="width: 75%; margin: 0 auto !important; padding-top: 3em;"
          enctype="multipart/form-data">
        <div class="ui large header">Registration</div>
        <div class="four fields">
            <div class="required field">
                <label>Email</label>
                <input type="email" name="email" v-model="email" placeholder="Enter your email here..."/>
            </div>
            <div class="required field">
                <label>Username</label>
                <input type="text" name="username" v-model="username" placeholder="Enter a username here..."/>
            </div>
            <div class="required field">
                <label>Password</label>
                <input type="password" name="password" v-model="password" placeholder="Enter a password here..."/>
            </div>
            <div class="required field">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" v-model="password_confirmation"
                       placeholder="Enter your password again here..."/>
            </div>
        </div>
        <div class="three fields">
            <div class="required field">
                <label>First Name</label>
                <input type="text" name="first_name" v-model="first_name" placeholder="Enter your first name here..."/>
            </div>
            <div class="required field">
                <label>Last Name</label>
                <input type="text" name="last_name" v-model="last_name" placeholder="Enter your last name here..."/>
            </div>
            <div class="required field">
                <label>Date of Birth</label>
                <date-picker :min-date="moment('1900-01-01')" :max-date="moment()"
                             @date="setDateOfBirth($event)"></date-picker>
            </div>
        </div>
        <div class="field">
            <label>Description</label>
            <textarea name="description" v-model="description" placeholder="Tell us something about yourself..."
                      :rows="setDescriptionRows(description)"
                      style="font-family: Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;">
            </textarea>
        </div>
        <div class="three fields">
            <div class="field">
                <label>Profile picture</label>
                <input type="hidden" name="picture" v-model="picture"/>
                <input type="file" name="pictureUpload" @change="filesChange($event.target.files)" accept="image/*"/>
            </div>
            <div class="required field">
                <label>Role</label>
                <div class="ui fluid buttons">
                    <div @click="setAsAuthor()" class="ui blue button" :class="{active: role===0}">Author</div>
                    <div class="or"></div>
                    <div @click="setAsReviewer()" class="ui red button" :class="{active: role===1}">Reviewer</div>
                </div>
            </div>
            <div class="field">
                <label>Actions</label>
                <div @click="register()" class="ui fluid buttons">
                    <div class="ui blue submit button">
                        Register
                    </div>
                    <div @click="clearRegisterFields()" class="ui clear button">
                        Clear
                    </div>
                </div>
            </div>
        </div>
        <div v-show="role===0" class="two fields">
            <div class="required field">
                <label>Organization Type</label>
                <div class="ui fluid buttons">
                    <div @click="setAsInstitute()" class="ui blue button" :class="{active: organization_type===0}">
                        Institute
                    </div>
                    <div class="or"></div>
                    <div @click="setAsCompany()" class="ui red button" :class="{active: organization_type===1}">
                        Company
                    </div>
                </div>
            </div>
            <div class="required field">
                <label>Organization Name</label>
                <input type="text" name="organization_name" v-model="organization_name"
                       placeholder="Enter the name of your organization..."/>
            </div>
        </div>
        <div v-show="role===0" class="four fields">
            <div class="required field">
                <label>Organization Country</label>
                <select class="ui search dropdown" name="organization_country" v-model="organization_country"
                        ref="countryDropdown">
                    <option value="">Choose your organization's country of residence...</option>
                    <option v-for="country in countries" :value="country">{{country}}</option>
                </select>
            </div>
            <div class="required field">
                <label>Organization Address</label>
                <input type="text" name="organization_address" v-model="organization_address"
                       placeholder="Enter the address of your organization..."/>
            </div>
            <div class="field">
                <label>Organization Email</label>
                <input type="email" name="organization_email" v-model="organization_email"
                       placeholder="Enter the contact email of your organization..."/>
            </div>
            <div class="field">
                <label>Organization Telephone</label>
                <input type="text" name="organization_telephone" v-model="organization_telephone"
                       placeholder="Enter the contact number of your organization..."/>
            </div>
        </div>
        <div v-if="validation_errors" class="ui visible error message" style="height: 15em; overflow-y: auto;">
            <i class="close icon"></i>
            <div class="header">
                Validation errors
            </div>
            <ul class="list" v-for="errors in Object.values(validation_errors)">
                <li v-for="error in errors">{{error}}</li>
            </ul>
        </div>
    </form>
</template>

<script>
    import moment from 'moment';
    import DatePicker from "./DatePicker";
    import toastr from 'toastr';
    import 'toastr/build/toastr.css';

    export default {
        name: "Register",
        components: {
            DatePicker
        },
        data() {
            return {
                'email': null,
                'username': null,
                'password': null,
                'password_confirmation': null,
                'first_name': null,
                'last_name': null,
                'description': null,
                'dob': null,
                'picture': null,
                'raw_image': null,
                'role': null,
                'moment': moment,
                'validation_errors': null,
                'organization_name': null,
                'organization_type': null,
                'organization_country': null,
                'organization_address': null,
                'organization_email': null,
                'organization_telephone': null,
                'countries': ['AF', 'AX', 'AL', 'DZ', 'AS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT',
                    'AZ', 'BH', 'BS', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BQ', 'BA', 'BW', 'BV', 'BR',
                    'IO', 'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 'CA', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO',
                    'KM', 'CG', 'CD', 'CK', 'CR', 'CI', 'HR', 'CU', 'CW', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'EC', 'EG',
                    'SV', 'GQ', 'ER', 'EE', 'ET', 'FK', 'FO', 'FJ', 'FI', 'FR', 'GF', 'PF', 'TF', 'GA', 'GM', 'GE', 'DE',
                    'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GU', 'GT', 'GG', 'GN', 'GW', 'GY', 'HT', 'HM', 'VA', 'HN', 'HK',
                    'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IM', 'IL', 'IT', 'JM', 'JP', 'JE', 'JO', 'KZ', 'KE', 'KI',
                    'KP', 'KR', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MG', 'MW',
                    'MY', 'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'YT', 'MX', 'FM', 'MD', 'MC', 'MN', 'ME', 'MS', 'MA',
                    'MZ', 'MM', 'NA', 'NR', 'NP', 'NL', 'NC', 'NZ', 'NI', 'NE', 'NG', 'NU', 'NF', 'MP', 'NO', 'OM', 'PK',
                    'PW', 'PS', 'PA', 'PG', 'PY', 'PE', 'PH', 'PN', 'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'BL',
                    'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS', 'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SX', 'SK',
                    'SI', 'SB', 'SO', 'ZA', 'GS', 'SS', 'ES', 'LK', 'SD', 'SR', 'SJ', 'SZ', 'SE', 'CH', 'SY', 'TW', 'TJ',
                    'TZ', 'TH', 'TL', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'US',
                    'UM', 'UY', 'UZ', 'VU', 'VE', 'VN', 'VG', 'VI', 'WF', 'EH', 'YE', 'ZM', 'ZW',
                ]
            }
        },
        methods: {
            checkIfUserLoggedIn() {
                fetch(`http://localhost:8000/users/check`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed checking whether any user is logged in'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed checking whether any user is logged in. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data)
                        window.location.href = '/';
                    return data
                })
            },
            setDescriptionRows(description) {
                let newLines = 0;
                if (description)
                    newLines = description.split('\n').length - 1;
                return newLines + 1
            },
            setDateOfBirth(date) {
                this.dob = moment(date).format('YYYY-MM-DD HH:mm:ss');
            },
            filesChange(filesList) {
                let imageFile = filesList[0];
                this.raw_image = imageFile;
                this.picture = imageFile.name
            },
            setAsAuthor() {
                this.role = 0;
            },
            setAsReviewer() {
                this.role = 1;
            },
            setAsInstitute() {
                this.organization_type = 0;
            },
            setAsCompany() {
                this.organization_type = 1;
            },
            uploadProfilePicture() {
                let formData = new FormData();
                formData.append('username', this.username);
                if (this.raw_image) {
                    formData.append('fileToUpload', this.raw_image);
                    fetch(`http://localhost:8000/users/upload`, {
                        method: 'POST',
                        body: formData,
                        credentials: 'include'
                    }).then(response => {
                        if (response.ok) {
                            return response.json()
                        } else {
                            return Promise.reject(new Error('Failed uploading profile picture.'))
                        }
                    }, reason => {
                        toastr.options.preventDuplicates = true;
                        toastr.error('Failed uploading picture.', 'ERROR');
                        return Promise.reject(reason)
                    }).then(data => {
                        if (data) {
                            toastr.options.preventDuplicates = true;
                            toastr.success('Profile picture uploaded.', 'SUCCESS');
                            window.location.href = '/';
                        }
                        return data
                    })
                } else
                    window.location.href = '/';
            },
            register() {
                let vm = this;
                let user = {
                    email: vm.email,
                    username: vm.username,
                    password: vm.password,
                    password_confirmation: vm.password_confirmation,
                    first_name: vm.first_name,
                    last_name: vm.last_name,
                    description: vm.description,
                    dob: vm.dob,
                    picture: vm.picture,
                    role: vm.role,
                    organization_type: vm.organization_type,
                    organization_name: vm.organization_name,
                    organization_country: vm.organization_country,
                    organization_address: vm.organization_address,
                    organization_email: vm.organization_email,
                    organization_telephone: vm.organization_telephone
                };
                fetch(`http://localhost:8000/users/register`, {
                    method: 'POST',
                    body: JSON.stringify(user),
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed adding new user'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed adding new user.', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data === "User account successfully created.") {
                        toastr.options.preventDuplicates = true;
                        toastr.success('User account created.', 'SUCCESS');
                        vm.validation_errors = null;
                        vm.uploadProfilePicture();
                    } else {
                        vm.validation_errors = data;
                        toastr.options.preventDuplicates = true;
                        toastr.warning('There were validation errors.', 'WARNING')
                    }
                    return data
                });
            },
            clearRegisterFields() {
                this.email = null;
                this.username = null;
                this.password = null;
                this.password_confirmation = null;
                this.first_name = null;
                this.last_name = null;
                this.description = null;
                this.dob = null;
                this.picture = null;
                this.raw_image = null;
            }
        },
        mounted() {
            this.checkIfUserLoggedIn();
            $(this.$refs.countryDropdown).dropdown();
        }
    }
</script>

<style scoped>

</style>