"""
Test script for Diet & Workout Recommendation System
Demonstrates the system with sample user profiles
"""

import pandas as pd
import pickle
import json
from sklearn.preprocessing import LabelEncoder

# Load trained models
calorie_model = pickle.load(open("models/calorie_model.pkl", "rb"))
intensity_model = pickle.load(open("models/intensity_model.pkl", "rb"))
le_gender = pickle.load(open("models/le_gender.pkl", "rb"))
le_goal = pickle.load(open("models/le_goal.pkl", "rb"))
le_disease = pickle.load(open("models/le_disease.pkl", "rb"))

# Load data
foods = pd.read_csv("foods.csv")
workouts = pd.read_csv("workout.csv")

def get_recommendation(age, gender, height_cm, weight_kg, goal, veg, disease):
    """Get recommendation for a user profile"""
    
    # Calculate BMI
    height_m = height_cm / 100
    bmi = weight_kg / (height_m ** 2)
    
    # Get BMI category
    if bmi < 18.5:
        bmi_category = "Underweight"
    elif bmi < 25:
        bmi_category = "Normal"
    elif bmi < 30:
        bmi_category = "Overweight"
    else:
        bmi_category = "Obese"
    
    # Encode inputs
    gender_enc = le_gender.transform([gender])[0]
    goal_enc = le_goal.transform([goal])[0]
    disease_enc = le_disease.transform([disease])[0]
    
    X = [[age, gender_enc, bmi, goal_enc, disease_enc]]
    
    # Get predictions
    daily_calories = int(calorie_model.predict(X)[0])
    intensity = intensity_model.predict(X)[0]
    
    return {
        "bmi": round(bmi, 2),
        "bmi_category": bmi_category,
        "daily_calories": daily_calories,
        "intensity": intensity
    }

# Sample user profiles to test
sample_users = [
    {
        "name": "John (Weight Loss, Diabetic)",
        "age": 35, "gender": "Male", "height": 175, "weight": 95,
        "goal": "weight_loss", "veg": 0, "disease": "diabetes"
    },
    {
        "name": "Sarah (Weight Gain, Healthy)",
        "age": 28, "gender": "Female", "height": 165, "weight": 58,
        "goal": "weight_gain", "veg": 1, "disease": "none"
    },
    {
        "name": "Mike (Maintain, High BP)",
        "age": 45, "gender": "Male", "height": 180, "weight": 85,
        "goal": "maintain", "veg": 0, "disease": "bp"
    },
    {
        "name": "Emma (Weight Loss, Heart Disease)",
        "age": 55, "gender": "Female", "height": 162, "weight": 78,
        "goal": "weight_loss", "veg": 1, "disease": "heart_disease"
    },
    {
        "name": "David (Weight Gain, Joint Problems)",
        "age": 22, "gender": "Male", "height": 172, "weight": 62,
        "goal": "weight_gain", "veg": 1, "disease": "joint_problems"
    }
]

print("=" * 80)
print("DIET & WORKOUT RECOMMENDATION SYSTEM - TEST RESULTS")
print("=" * 80)

for user in sample_users:
    print(f"\n{'='*80}")
    print(f"User Profile: {user['name']}")
    print(f"{'='*80}")
    print(f"Age: {user['age']} | Gender: {user['gender']}")
    print(f"Height: {user['height']}cm | Weight: {user['weight']}kg")
    print(f"Goal: {user['goal']} | Diet: {'Vegetarian' if user['veg'] else 'Non-Vegetarian'}")
    print(f"Health Condition: {user['disease']}")
    
    # Get recommendation
    rec = get_recommendation(
        user['age'], user['gender'], user['height'], user['weight'],
        user['goal'], user['veg'], user['disease']
    )
    
    print(f"\n--- RECOMMENDATIONS ---")
    print(f"BMI: {rec['bmi']} ({rec['bmi_category']})")
    print(f"Daily Calorie Target: {rec['daily_calories']} calories")
    print(f"Recommended Intensity: {rec['intensity']}")
    
    # Show sample foods
    print(f"\n--- SAMPLE FOODS (based on preferences) ---")
    if user['veg']:
        sample_foods = foods[foods['veg'] == 1].sample(3)
    else:
        sample_foods = foods.sample(3)
    
    for idx, food in sample_foods.iterrows():
        print(f"  • {food['food_name']}: {food['calories']} cal | "
              f"P:{food['protein']}g C:{food['carbs']}g F:{food['fats']}g")
    
    # Show sample workouts
    print(f"\n--- SAMPLE WORKOUTS (based on intensity) ---")
    sample_workouts = workouts.sample(3)
    for idx, workout in sample_workouts.iterrows():
        print(f"  • {workout['workout_name']}: {workout['duration_minutes']} min | "
              f"{workout['calories_burned']} cal burn | Difficulty: {workout['difficulty']}")

print(f"\n{'='*80}")
print("Testing Complete!")
print("Start the Flask app with: python app.py")
print(f"{'='*80}\n")
