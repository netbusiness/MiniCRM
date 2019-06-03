<template>
  <div class="container">
    <div class="row mb-3"><button class="btn btn-success" v-on:click="newManager()">Add New Manager</button></div>
    <div class="row">
      <div class="col-md-4 mb-3" v-for="manager in managers">
        <div class="card">
          <div class="card-header">{{ manager.name }}</div>
          <div class="card-body">
            <p>{{ manager.email }}</p>
            <div>
              <a href="#" class="btn btn-primary" v-on:click="editManager(manager)">Edit</a>
              <a href="#" class="btn btn-danger float-right" v-on:click="confirmDelete(manager)">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <paginator v-bind:totalPages="totalPages" v-bind:pageNumber="pageNumber" v-on:changePage="gotoPage($event)"></paginator>
    <div id="addManagerModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="isNewManager" class="modal-title">Add New Manager</h5>
            <h5 v-else class="modal-title">Edit Manager</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="form-group">
                <label for="newManagerName">Name*</label>
                <input type="text" name="name" id="newManagerName" class="form-control" v-model="managerName">
              </div>
              <div class="form-group">
                <label for="newManagerEmail">Email*</label>
                <input type="text" name="email" id="newManagerEmail" class="form-control" v-model="managerEmail">
              </div>
              <div class="form-group">
                <label for="">Companies*</label>
                <select multiple name="companies[]" id="newManagerCompanies">
                  <option v-for="company in companies" v-bind:value="company.id" v-bind:selected="managerCompanies.includes(company.id)">{{ company.name }}</option>
                </select>
              </div>
              <div class="form-group" v-if="isNewManager">
                <label for="newManagerPhone">Password*</label>
                <input type="password" name="password" id="newManagerPassword" class="form-control">
              </div>
              <div class="form-group" v-if="isNewManager">
                <label for="newManagerPhone">Confirm Password*</label>
                <input type="password" name="password_confirmation" id="newManagerPasswordConfirmation" class="form-control">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="saveManager()">Save changes</button>
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
        managers: [],
        companies: [],
        pageNumber: 1,
        totalPages: 1,
        managerId: null,
        managerName: '',
        managerEmail: '',
        managerCompanies: [],
        isNewManager: false
      };
    },
    methods: {
      newManager() {
        this.managerId = null;
        this.managerName = '';
        this.managerEmail = '';
        this.managerCompanies = [];
        this.isNewManager = true;
        $("#addManagerModal").modal("show");
      },
      flattenCompanies: function(input) {
        console.log(input);
        var ids = [];
        for (var i in input) {
          ids.push(input[i].id);
        }
        console.log(ids);
        return ids;
      },
      editManager: function(manager) {
        this.managerId = manager.id;
        this.managerName = manager.name;
        this.managerEmail = manager.email;
        this.managerCompanies = this.flattenCompanies(manager.companies);
        this.isNewManager = false;
        $("#addManagerModal").modal("show");
      },
      saveManager: function(bogus /* Need a param here or it's "nOt a fUnCtIoN" */) {
        console.log("bar");
        var formData = new FormData(document.getElementById("newManagerName").form);
        
        if (this.isNewManager) {
          this.create(formData);
        } else {
          formData.append("_method", "put"); // Work around dumb bug in laravel
          this.update(this.managerId, formData);
        }
        
        $("#addManagerModal").modal("hide");
      },
      confirmDelete: function(manager) {
        bootbox.confirm("Are you sure you want to delete " + manager.name + "?", (yesNo) => {
          if (yesNo) {
            this.delete(manager.id);
          }
        });
      },
      gotoPage: function(pageNumber) {
        console.log(pageNumber);
        this.pageNumber = pageNumber;
        this.read();
      },
      read() {
        window.axios.get("/managers", {params: {
          page: this.pageNumber
        }}).then(({data}) => {
          console.log(data);
          this.managers = data.data;
          this.totalPages = data.last_page;
          this.pageNumber = data.current_page;
        });
      },
      update(id, manager) {
        window.axios.post(`/managers/${id}`, manager, {
          headers: {'content-type': 'multipart/form-data'}
        }).then(() => {
          this.read();
        }/*, (error) => {
          console.log(error.response);
        } */);
      },
      create(manager) {
        window.axios.post("/managers", manager, {
          headers: {'content-type': 'multipart/form-data'}
        }).then(() => {
          this.read();
        });
      },
      delete(id) {
        window.axios.delete(`/managers/${id}`).then(() => {
          this.read();
        });
      }
    },
    created() {
      this.read();
      window.axios.get("/companies", {params: {all: 1}}).then(({data}) => {
        this.companies = data;
      });
    }
  }
</script>