<template>
  <nav class="mt-3" v-if="totalPages > 1">
    <ul class="pagination justify-content-center">
      <li class="page-item" v-bind:class="{disabled: pageNumber == 1}" v-on:click="forward()"><a class="page-link" href="#">&lt;</a></li>
      <li class="page-item" v-bind:class="{active: pageNumber == i}" v-for="i in totalPages" v-on:click="gotoPage(i)"><a class="page-link" href="#">{{ i }}</a></li>
      <li class="page-item" v-bind:class="{disabled: pageNumber == totalPages}" v-on:click="backward()"><a class="page-link" href="#">&gt;</a></li>
    </ul>
  </nav>
</template>

<script>
  export default {
    props: ['totalPages', 'pageNumber'],
    methods: {
      forward() {
        if (this.pageNumber == 1) {
          return;
        }
        
        this.$emit("changePage", this.pageNumber - 1);
      },
      backward() {
        if (this.pageNumber == this.totalPages) {
          return;
        }
        
        this.$emit("changePage", this.pageNumber + 1);
      },
      gotoPage(n) {
        this.$emit("changePage", n);
      }
    }
  }
</script>