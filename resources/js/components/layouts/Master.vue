<template>
    <div>
        <header-layout></header-layout>
        <router-view></router-view>
        <footer-layout></footer-layout>
    </div>
</template>

<script>
    import HeaderLayout from './Header';
    import FooterLayout from './Footer';

    export default {
        components: {
            HeaderLayout,
            FooterLayout
        }
    }
</script>
