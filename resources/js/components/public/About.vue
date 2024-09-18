<template>
    <div class="container bg-container mt-5">
        <div class="row">
            <div class="col-md-12 mt-3 mb-5">
                <div class="head-about">
                    About
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-about">
                    <div class="title">
                        RECHIVA
                    </div>
                    <p class="content">&nbsp;&nbsp;&nbsp;&nbsp;
                        Is a web-based repository system designed to simplify the management and sharing of research publications in academic institutions. Our platform provides a smooth experience for students, faculty, and administrators to access, submit, and manage scholarly work.
                    </p>
                    <p class="content">&nbsp;&nbsp;&nbsp;&nbsp;
                    Acts as a central hub for academic research, making it easy to manage and access publications. We aim to improve the visibility and accessibility of scholarly materials, support collaboration, and promote research excellence.
                    </p>
                </div>
            </div>
              <div class="col-md-6 mt-3">
                <div class="content-about">
                    <div class="title">
                        Vision :
                    </div>
                    <p class="content text-start text-size">
                    Our mission is to support and enrich the academic community by providing a comprehensive, intuitive platform for the management of research publications. We are committed to facilitating seamless collaboration among students, faculty, and administrators. By enhancing the visibility and accessibility of scholarly work, we aim to contribute significantly to the progress of research and education, fostering an environment where academic excellence can thrive.
                    </p>
                </div>
            </div>
              <div class="col-md-6 mt-3">
                <div class="content-about">
                    <div class="title">
                        Mission :
                    </div>
                    <p class="content text-start text-size">
                     To establish ourselves as the leading platform in academic research management, dedicated to advancing knowledge through cutting-edge, user-centric solutions. We envision a future where our innovative tools and services empower the academic community to achieve new heights in research and collaboration.
                    </p>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="contact text-start p-5">
                    <h4 class="head-title">Contact Information:</h4>
                    <div class="contact-email">
                        <div class="title">
                        RECHIVA Development Center (RDC):
                        </div>
                        <p>
                        Email address : rdc@ndmc.edu.ph
                        </p>
                    </div>

                    <div class="contact-social mt-2">
                        <div class="title">
                       Follow Us:
                        </div>
                        <p>
                        Stay connected and updated with our latest news and updates through our social media channels:
                        </p>
                         <div class="title">
                            Facebook: 
                            <a href="https://facebook.com/rechiva">https://facebook.com/rechiva</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .contact{
        .contact-email{
            font-size: 1rem;
            font-weight: 500;
            .title{
                  font-weight: 800;
            }
        }
        .contact-social{
            font-size: 1rem;
            font-weight: 500;
            .title{
                  font-weight: 800;
            }
        }
    }
    .bg-container{
        background-color: #fff;
        // min-height: 40rem;

        margin-bottom: 10rem !important
    }
    .content-about{
        text-align: left !important;
        .title{
            font-size: 1.5rem;
            font-weight: 700;
            padding:0 10px;
            border-radius: 4px;
            background-color: #07ce82;
            color:#fff;
            width: 25%;
            margin-bottom: 10px;
            border:#26884b solid 1px;
            -webkit-box-shadow: 0 1px 1px #6b6b6b49;
            box-shadow: 0 1px 1px #6464646e;
        }
        .content{
             font-size: 1.2rem;
             padding-left: 1.5rem;
             font-weight: 500;
             color: #414141;
        }
    }

    .head-about{
        font-size: 1.5rem;
        font-weight: 700;
        background-color: #133921;
        color:#fff;
    }

    .text-size{
        font-size: 1.1rem !important;
        font-weight: 500 !important;
    }
    .head-title{
        font-size: 1.8rem;
    }
</style>
