class AddUserToRuc < ActiveRecord::Migration
  def change
    add_column :Users, :ruc, :string
  end
end
