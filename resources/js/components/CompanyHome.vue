<template>
  <div class="container">
    <div class="row mb-3" v-if="userData.access_level == 10"><button class="btn btn-success" v-on:click="newCompany()">Add New Company</button></div>
    <div class="row">
      <div class="col-md-4 mb-3" v-for="company in companies">
        <div class="card">
          <a v-bind:href="companyPage(company.id)">
            <img class="card-img-top" v-bind:src="company.logo || image">
            <div class="card-header">{{ company.name }}</div>
          </a>
          <div class="card-body">
            <p>{{ company.email }}</p>
            <p>{{ company.website }}</p>
            <div v-if="userData.access_level == 10">
              <a href="#" class="btn btn-primary" v-on:click="editCompany(company)">Edit</a>
              <a href="#" class="btn btn-danger float-right" v-on:click="confirmDelete(company)">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <paginator v-bind:totalPages="totalPages" v-bind:pageNumber="pageNumber" v-on:changePage="gotoPage($event)"></paginator>
    <div id="addCompanyModel" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="isNewCompany" class="modal-title">Add New Company</h5>
            <h5 v-else class="modal-title">Edit Company</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="form-group">
                <label for="newCompanyName">Name*</label>
                <input type="text" name="name" id="newCompanyName" class="form-control" v-model="companyName">
              </div>
              <div class="form-group">
                <label for="newCompanyEmail">Email</label>
                <input type="text" name="email" id="newCompanyEmail" class="form-control" v-model="companyEmail">
              </div>
              <div class="form-group">
                <label for="newCompanyWebsite">Website</label>
                <input type="text" name="website" id="newCompanyWebsite" class="form-control" v-model="companyWebsite">
              </div>
              <div class="form-group">
                <label for="newCompanyLogo">Logo</label>
                <input type="file" name="logo" id="newCompanyLogo" class="form-control" v-on:change="onImageChange">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="saveCompany()">Save changes</button>
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
        companies: [],
        pageNumber: 1,
        totalPages: 1,
        companyId: null,
        companyName: '',
        companyLogo: null,
        companyEmail: '',
        companyWebsite: '',
        isNewCompany: false
      };
    },
    props: ['userData'],
    computed: {
      image: function() {
        return "https://via.placeholder.com/150";
      }
    },
    methods: {
      companyPage(company_id) {
        return "/home/company/" + company_id;
      },
      confirmDelete: function(company) {
        bootbox.confirm("Are you sure you want to delete " + company.name + "?", (yesNo) => {
          if (yesNo) {
            this.delete(company.id);
          }
        });
      },
      editCompany: function(company) {
        this.companyId = company.id;
        this.companyName = company.name;
        this.companyEmail = company.email;
        this.companyLogo = company.logo;
        this.isNewCompany = false;
        $("#addCompanyModel").modal("show");
      },
      gotoPage: function(pageNumber) {
        this.pageNumber = pageNumber;
        this.read();
      },
      onImageChange: function(event) {
        var files = event.target.files || event.dataTransfer.files;
        if (!files.length) {
          return;
        }
        
        var reader = new FileReader();
        var vm = this;
        reader.onload = (e) => {
          vm.companyLogo = e.target.result;
          console.log(vm.companyLogo);
        };
        reader.readAsDataURL(files[0]);
      },
      newCompany() {
        $("#newCompanyLogo").get()[0].form.reset(); // Clear file dialog
        this.companyId = null;
        this.companyName = '';
        this.companyLogo = null;
        this.companyEmail = '';
        this.companyWebsite = '';
        this.isNewCompany = true;
        $("#addCompanyModel").modal("show");
      },
      saveCompany() {
        /* var company = {};
        company.id = this.companyId;
        company.name = this.companyName;
        company.email = this.companyEmail;
        company.website = this.companyWebsite;
        company.logo = this.companyLogo; */
        
        var formData = new FormData(document.getElementById("newCompanyLogo").form);
        
        if (this.isNewCompany) {
          this.create(formData);
        } else {
          formData.append("_method", "put"); // Work around dumb bug in laravel
          this.update(this.companyId, formData);
        }
        
        $("#addCompanyModel").modal("hide");
      },
      read() {
        // let url = "/companies?page=" + this.pageNumber;
        window.axios.get("/companies", {params: {page: this.pageNumber}}).then(({data}) => {
          console.log(data);
          this.companies = data.data;
          this.totalPages = data.last_page;
          this.pageNumber = data.current_page;
        });
      },
      create(company) {
        window.axios.post("/companies", company, {
          headers: {'content-type': 'multipart/form-data'}
        }).then(() => {
          this.read();
        });
      },
      update(id, company) {
        console.log(id, company);
        window.axios.post(`/companies/${id}`, company, {
          headers: {'content-type': 'multipart/form-data'}
        }).then(() => {
          this.read();
        });
      },
      delete(id) {
        window.axios.delete(`/companies/${id}`).then(() => {
          this.read();
        });
      }
    },
    created() {
      this.read();
    }
  }
</script>