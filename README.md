# Learn.Py - A Beginner's Guide to Python

Welcome to **Learn.Py**, an interactive and user-friendly website designed to help beginners learn Python programming. This project follows modern web development standards, ensuring a structured, visually appealing, and accessible learning experience.

## Features

- **Well-Structured HTML Pages**: Organized content using semantic HTML5 elements.
- **Stylish Design**: A consistent and responsive design using an external CSS file.
- **Easy Navigation**: A fully functional navigation menu for seamless browsing.
- **Interactive Elements**: Includes JavaScript-based features for better user engagement.
- **Educational Content**: Integrates multimedia elements like images and videos.
- **Accessibility**: Optimized for users with disabilities.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/LearnPy.git
   ```
2. Navigate to the project directory:
   ```bash
   cd LearnPy
   ```
3. Open `index.html` in your preferred browser.

## Usage

- Explore Python concepts through structured lessons.
- Navigate through different topics using the menu.
- Interact with JavaScript-based features for an engaging experience.

## License

This project is licensed under the MIT License.

# Enhanced FrontEnd

- Added 3 quiz pages at end of every chapter (quiz0.html, quiz1.html, and quiz2.html)
- Added profile page where users can track their quiz grades (profile.html)
- Added admin page where admins can add questions to any quiz (admin.html)

# JQuery

JQuery was used in client side scripting for the quiz pages (javascript/quiz.js)

# Ajax

# DataBase
Our database is made up of 4 tables
- users :Id, FirstName, LastName, Email, Is_admin, Password(hashed),  q0(quiz 0 grade), q1 , and q2
- q0(quiz 0) : Id, question, op1 (option1) , op2, op3, op4, answer(option 1,2,3,or 4)
- q1 : Id, question, op1, op2, op3, op4, answer
- q2 : Id, question, op1, op2, op3, op4, answer

# Authors

- Mazen Kaikaty
- Jamal El Kassar
- Adam Hamidi Sakr

## Resources
- Bootstrap for homepage Excluding navbar and footer. home icon in sidebar in all course pages.
- Chatgpt assisted scripting for shrinking nested lists in sidebar for course pages, API skeleton for example code compiler, disapearing terms and conditions div, and complex Jquery and php syntax.
- Embed for compiler from tinker.io. API for compiler is emkc.org free API
- Images are a screen grab from Anaconda's Spyder console.
- Font is IBM Plex Sans from Google Fonts
- Course content is derived from a mix of public sources and Lau's CSC243 documents.

## Contact

For questions or suggestions, feel free to open an issue on GitHub.

---

Enjoy learning Python with **Learn.Py**!

