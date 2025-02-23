import kagglehub
import shutil
import os


def download_kaggle_datasets(destination_path):
    # Download the dataset from Kaggle
    path = kagglehub.dataset_download("shuyangli94/food-com-recipes-and-user-interactions")

    # Ensure the destination directory exists
    if not os.path.exists(destination_path):
        os.makedirs(destination_path)

    # Copy the downloaded dataset to the specified directory
    for file_name in os.listdir(path):
        full_file_path = os.path.join(path, file_name)
        if os.path.isfile(full_file_path):
            shutil.copy(full_file_path, destination_path)

    print(f"Dataset saved to: {destination_path}")


if __name__ == "__main__":
    destination = "/data"  # Relative path
    download_kaggle_datasets(destination)
