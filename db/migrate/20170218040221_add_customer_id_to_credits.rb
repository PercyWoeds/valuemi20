class AddCustomerIdToCredits < ActiveRecord::Migration
  def change
    add_column :credits, :customer_id, :integer
  end
end
