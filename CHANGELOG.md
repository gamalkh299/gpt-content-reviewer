## v1.0 - 2024-12-23

### Release Notes for GPT Content Reviewer - v1.0

We are excited to announce the initial release of **GPT Content Reviewer**! 🎉

This Laravel package allows you to review text and images in your application using the ChatGPT Moderation API. It provides a seamless way to ensure your content meets the required standards, powered by AI.


---

#### 🚀 Key Features

- **Text and Image Review**: Easily review text content or images via URL or Base64.
- **Model Integration**: Add the `Reviewable` trait to your models for automatic review handling.
- **Customizable Review Logic**: Define the columns to be reviewed and handle review results with abstract methods.
- **Queue Integration**: Asynchronous review processing through Laravel queues for efficient handling of large datasets.
- **API Key Configuration**: Simple `.env` integration to set your ChatGPT API key securely.


