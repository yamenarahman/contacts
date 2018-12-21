<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h3><i class="fa fa-users"></i> All contacts ({{ contacts.length }})
            <button class="btn btn-outline-primary pull-right" data-toggle="modal" data-target="#create-contact">
              <i class="fa fa-plus"></i>
            </button>
          </h3>
          <!-- Create new contact Modal -->
          <div class="modal fade" id="create-contact" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add new contact</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group row p-2">
                    <label for="name" class="col-2">Name</label>
                    <input type="text" class="form-control col-10" id="name" v-model="name" placeholder="contact name...">
                  </div>
                  <div class="form-group row p-2">
                    <label class="col-2">Phone</label>
                    <vue-tel-input v-model="phone"></vue-tel-input>
                  </div>
                  <div class="form-group row p-2">
                    <label for="email" class="col-2">Email</label>
                    <input type="email" class="form-control col-10" id="email" v-model="email" placeholder="email...">
                  </div>
                  <div class="form-group row p-2">
                    <label for="company" class="col-2">Company</label>
                    <input type="text" class="form-control col-10" id="company" v-model="company" placeholder="company">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" @click="store()">Save</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <ul class="list-group" v-if="contacts.length > 0">
        <li class="list-group-item my-2" v-for="(contact, index) in contacts" :key="index">
          <div class="row w-100 d-flex">
            <div class="col-2 col-sm-6 col-md-3 px-0 d-flex justify-content-center align-items-center">
              <h1><i class="fa fa-user"></i></h1>
            </div>
            <div class="col-10 col-sm-6 col-md-9 text-center text-sm-left">
              <label class="name lead">{{ contact.name }}</label>
              <br>
              <span class="fa fa-phone fa-fw text-muted"></span>
              <span class="text-muted small">{{ contact.phone }}</span>
              <br>
              <span class="fa fa-envelope fa-fw text-muted"></span>
              <span class="text-muted small text-truncate">{{ contact.email }}</span>
              <br>
              <span class="fa fa-map-marker fa-fw text-muted"></span>
              <span class="text-muted">{{ contact.company }}</span>
              <br>
              <span class="fa fa-calendar fa-fw text-muted"></span>
              <span class="text-muted">{{ contact.created_at }}</span>
              <button href="" class="btn btn-danger pull-right mx-1" @click="destroy(contact, index)"><i class="fa fa-trash"></i>
              </button>
              <button class="btn btn-outline-info pull-right" data-toggle="modal" data-target="#edit-contact" @click="edit(contact)"><i
                class="fa fa-pencil-square-o"></i></button>
            </div>
          </div>
        </li>
      </ul>
      <h4 v-else class="text-center">No contacts yet.</h4>
      <div class="modal fade" id="edit-contact" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit contact</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row p-2">
                <label for="edit_name" class="col-2">Name</label>
                <input type="text" class="form-control col-10" id="edit_name" v-model="editContact.name" placeholder="contact name...">
              </div>
              <div class="form-group row p-2">
                <label class="col-2">Phone</label>
                <vue-tel-input v-model="editContact.phone"></vue-tel-input>
              </div>
              <div class="form-group row p-2">
                <label for="edit_email" class="col-2">Email</label>
                <input type="email" class="form-control col-10" id="edit_email" v-model="editContact.email" placeholder="email...">
              </div>
              <div class="form-group row p-2">
                <label for="edit_company" class="col-2">Company</label>
                <input type="text" class="form-control col-10" id="edit_company" v-model="editContact.company" placeholder="company">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="update()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        contacts: [],
        name: '',
        phone: '',
        email: '',
        company: '',
        editContact: {}
      }
    },
    mounted() {
      axios.get('/api/contacts').then(response => {
        this.contacts = response.data.contacts;
      }).catch(error => {
        console.log(error.message);
      });
    },
    methods: {
      store() {
        axios.post('/api/contacts', {
          name: this.name,
          phone: this.phone,
          email: this.email,
          company: this.company
        }).then(response => {
          if (response.data.type === 'success') {
            this.contacts.push(response.data.contact);
            this.reset();
            swal('Contact has been added!', {
              icon: 'success',
            });
            $('#create-contact').modal('hide');
          }
        }).catch(error => {
          swal('error', error.response.data.message, 'error');
        });
      },
      edit(contact) {
        this.editContact = {
          id: contact.id,
          name: contact.name,
          phone: contact.phone,
          email: contact.email,
          company: contact.company
        };
      },
      update() {
        axios.patch('/api/contacts/' + this.editContact.id, {
          name: this.editContact.name,
          phone: this.editContact.phone,
          email: this.editContact.email,
          company: this.editContact.company,
        }).then(response => {
          if (response.data.type === 'success') {
            var updatedContact = this.contacts.find(contact => {
              return contact.id === this.editContact.id;
            });

            updatedContact.name = response.data.contact.name;
            updatedContact.phone = response.data.contact.phone;
            updatedContact.email = response.data.contact.email;
            updatedContact.company = response.data.contact.company;

            swal('Contact has been updated!', {
              icon: 'success',
            });
            $('#edit-contact').modal('hide');
          }
        }).catch(error => {
          swal('error', error.response.data.message, 'error');
        });
      },
      destroy(contact, index) {
        swal({
          title: 'Are you sure to delete ' + contact.name + ' ?',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            axios.delete('/api/contacts/' + contact.id)
              .then(response => {
                if (response.data.type === 'success') {
                  swal('Contact has been deleted!', {
                    icon: 'success',
                  });
                  this.contacts.splice(index, 1);
                }
              })
              .catch(error => {
                console.log(error.message);
              });
          }
        });
      },
      reset() {
        this.name = '';
        this.phone = '';
        this.email = '';
        this.company = '';
      }
    }
  }
</script>
