from flask import Flask, request, jsonify, render_template_string
from flask_cors import CORS
import pandas as pd
import pickle
import random

app = Flask(__name__)
CORS(app)

# ================= LOAD DATA =================

foods = pd.read_csv("foods.csv")
workouts = pd.read_csv("workout.csv")

calorie_model = pickle.load(open("models/calorie_model.pkl", "rb"))
intensity_model = pickle.load(open("models/intensity_model.pkl", "rb"))
le_gender = pickle.load(open("models/le_gender.pkl", "rb"))
le_goal = pickle.load(open("models/le_goal.pkl", "rb"))
le_disease = pickle.load(open("models/le_disease.pkl", "rb"))

# ================= HELPERS =================

def calculate_bmi(height_cm, weight_kg):
    return round(weight_kg / ((height_cm / 100) ** 2), 2)

def get_bmi_category(bmi):
    if bmi < 18.5:
        return "Underweight"
    elif bmi < 25:
        return "Normal"
    elif bmi < 30:
        return "Overweight"
    return "Obese"

def validate_goal(bmi, goal):
    if bmi >= 25 and goal == "weight_gain":
        return "weight_loss"
    return goal

def filter_food(df, veg, disease):
    f = df.copy()
    if veg == 1:
        f = f[f["veg"] == 1]

    if disease != "none":
        col = disease + "_safe"
        if col in f.columns:
            f = f[f[col] == 1]

    return f

def filter_workout(df, disease, intensity):
    w = df.copy()

    if disease != "none":
        col = disease + "_safe"
        if col in w.columns:
            w = w[w[col] == 1]

    if intensity == "Easy":
        w = w[w["difficulty"] == "Easy"]
    elif intensity == "Medium":
        w = w[w["difficulty"].isin(["Easy", "Medium"])]
    else:
        w = w[w["difficulty"].isin(["Medium", "Hard"])]

    return w

def generate_workout_plan(workouts, calories, goal):
    target = calories * (0.6 if goal == "weight_loss" else 0.3)
    plan = []

    for i, (_, w) in enumerate(workouts.sample(min(7, len(workouts))).iterrows()):
        plan.append({
            "day": f"Day {i+1}",
            "workout": w["workout_name"],
            "duration": int(w["duration_minutes"]),
            "calories_burned": int(w["calories_burned"]),
            "difficulty": w["difficulty"]
        })

    return {
        "daily_target": int(target),
        "workouts": plan
    }

# ================= API =================

@app.route("/recommend", methods=["POST"])
def recommend():
    try:
        data = request.json

        age = int(data["age"])
        gender = data["gender"]
        bmi = float(data["bmi"])
        goal = data["goal"]
        veg = int(data["veg"])
        disease = data["disease"]

        goal = validate_goal(bmi, goal)

        X = [[
            age,
            le_gender.transform([gender])[0],
            bmi,
            le_goal.transform([goal])[0],
            le_disease.transform([disease])[0]
        ]]

        daily_calories = int(calorie_model.predict(X)[0])
        intensity = intensity_model.predict(X)[0]

        foods_f = filter_food(foods, veg, disease)
        workouts_f = filter_workout(workouts, disease, intensity)

        diet_plan = []
        for i in range(3):
            meal = foods_f.sample(min(3, len(foods_f)))
            diet_plan.append({
                f"Meal {i+1}": {
                    "foods": meal.to_dict(orient="records"),
                    "total_calories": int(meal["calories"].sum())
                }
            })

        workout_plan = generate_workout_plan(workouts_f, daily_calories, goal)

        return jsonify({
            "bmi": bmi,
            "bmi_category": get_bmi_category(bmi),
            "daily_calories": daily_calories,
            "workout_intensity": intensity,
            "diet_plan": diet_plan,
            "workout_plan": workout_plan
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 400


if __name__ == "__main__":
    app.run(debug=True, port=5000)
